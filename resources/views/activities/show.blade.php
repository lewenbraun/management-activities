<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activity->name }} - Activity Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css"
        integrity="sha512-Zcn6bjR/8RZbLEpLIeOwNtzREBAJnUKESxces60Mpoj+2okopSAcSUIUOseddDm0cxnGQzxIR7vJgsLZbdLE3w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"
        integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        #mapid {
            height: 400px;
            width: 100%;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('activities.index') }}"
            class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            ← Back to all activities
        </a>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if ($activity->media->isNotEmpty())
                <div class="h-96 overflow-hidden">
                    <img src="{{ $activity->media->first()->getUrl() }}" alt="{{ $activity->name }}"
                        class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $activity->name }}</h1>
                        <div class="mt-2 flex items-center">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $activity->activityType->name }}
                            </span>
                            <span class="ml-3 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $activity->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    @auth
                        <button
                            class="favorite-btn text-3xl {{ $activity->favoritedByUsers->isNotEmpty() ? 'text-yellow-500' : 'text-gray-300' }}"
                            data-activity-id="{{ $activity->id }}">
                            ★
                        </button>
                    @endauth
                </div>

                <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="prose max-w-none">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                            <p class="text-gray-700">{{ $activity->description }}</p>

                            @if ($activity->short_description)
                                <h3 class="text-lg font-medium text-gray-800 mt-6">Short Description</h3>
                                <p class="text-gray-700">{{ $activity->short_description }}</p>
                            @endif
                        </div>

                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Schedule & Location</h2>

                            @if ($activity->schedule)
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <h3 class="font-medium text-gray-800">Schedule</h3>
                                    <ul class="mt-2 text-sm text-gray-700">
                                        {{-- Изменено: $activity->schedule уже является массивом благодаря $casts --}}
                                        @foreach ($activity->schedule as $item)
                                            <li>
                                                <span class="font-semibold">Date:</span>
                                                {{ \Carbon\Carbon::parse($item['date'])->format('F d, Y') }}
                                                <span class="ml-4 font-semibold">Time:</span>
                                                {{ \Carbon\Carbon::parse($item['time'])->format('H:i') }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($activity->coordinates)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h3 class="font-medium text-gray-800 mb-2">Coordinates</h3>
                                    <div id="mapid"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Activity Details</h2>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Activity Type</h3>
                                    <p class="mt-1 text-gray-800">{{ $activity->activityType->name }}</p>
                                    @if ($activity->activityType->icon_name)
                                        <p class="text-sm text-gray-600">Icon: {{ $activity->activityType->icon_name }}
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Organizer</h3>
                                    <p class="mt-1 text-gray-800">{{ $activity->creator->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity->creator->email }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity->creator->phone_number }}</p>
                                </div>

                                @if ($activity->participant)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Participant</h3>
                                        <p class="mt-1 text-gray-800">{{ $activity->participant->name }}</p>
                                        @if ($activity->participant->website_link)
                                            <a href="{{ $activity->participant->website_link }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 text-sm block mt-1">
                                                {{ $activity->participant->website_link }}
                                            </a>
                                        @endif
                                        @if ($activity->participant->location_description)
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $activity->participant->location_description }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if ($activity->source)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Source</h3>
                                        <p class="mt-1 text-gray-800">{{ $activity->source }}</p>
                                    </div>
                                @endif

                                @if ($activity->registration_link)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Registration</h3>
                                        <a href="{{ $activity->registration_link }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium block mt-1">
                                            Register Now
                                        </a>
                                    </div>
                                @endif

                                <div class="pt-4 border-t border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                                    <p class="mt-1 text-gray-800">{{ $activity->created_at->format('M d, Y H:i') }}</p>

                                    <h3 class="text-sm font-medium text-gray-500 mt-3">Updated At</h3>
                                    <p class="mt-1 text-gray-800">{{ $activity->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($activity->media as $media)
                            <div class="overflow-hidden rounded-lg">
                                <img src="{{ $media->getUrl() }}" alt="Activity Media"
                                    class="w-full h-48 object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', function() {
                const activityId = this.getAttribute('data-activity-id');
                const btn = this;

                axios.post(`/activities/${activityId}/toggle-favorite`)
                    .then(response => {
                        if (response.data.isFavorite) {
                            btn.classList.remove('text-gray-300');
                            btn.classList.add('text-yellow-500');
                        } else {
                            btn.classList.remove('text-yellow-500');
                            btn.classList.add('text-gray-300');
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 401) {
                            alert('Please login to add favorites');
                        } else {
                            alert('Error occurred');
                        }
                    });
            });
        });

        @if ($activity->coordinates)
            const coordinates = @json($activity->coordinates);

            if (coordinates && coordinates.length > 0) {
                const map = L.map('mapid').setView([coordinates[0].lat, coordinates[0].lng], 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const latLngs = coordinates.map(coord => [coord['lat'], coord['lng']]);

                const polygon = L.polygon(latLngs, {
                    color: 'blue',
                    fillColor: '#ADD8E6',
                    fillOpacity: 0.5
                }).addTo(map);

                map.fitBounds(polygon.getBounds());
            }
        @endif
    </script>
</body>

</html>
