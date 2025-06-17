<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Activities</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<header class="flex justify-between items-center p-4 bg-gray-100 border-b border-gray-200">
    <div>
        <a href="/" class="text-xl font-bold text-gray-800">Activities</a>
    </div>
    <nav class="flex items-center gap-4">
        @auth
            <span class="text-gray-700">{{ Auth::user()->name }}!</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-200 ease-in-out">
                    Log out
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"
                class="px-4 py-2 text-blue-700 border border-blue-700 rounded hover:bg-blue-100 transition duration-200 ease-in-out">
                >Log in
            </a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200 ease-in-out">
                    Register
                </a>
            @endif
        @endauth
    </nav>
</header>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">All Activities</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($activities as $activity)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    @if ($activity->media->isNotEmpty())
                        <img src="{{ $activity->media->first()->getUrl() }}" alt="{{ $activity->name }}"
                            class="w-full h-48 object-cover">
                    @endif

                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-semibold text-gray-800">
                                <a href="{{ route('activities.show', $activity) }}" class="hover:underline">
                                    {{ $activity->name }}
                                </a>
                            </h2>

                            @auth
                                <button
                                    class="favorite-btn text-2xl {{ in_array($activity->id, $favorites) ? 'text-yellow-500' : 'text-gray-300' }}"
                                    data-activity-id="{{ $activity->id }}">
                                    ★
                                </button>
                            @endauth
                        </div>

                        <p class="text-gray-600 mt-2">
                            {{ $activity->short_description ?? Str::limit($activity->description, 100) }}
                        </p>

                        <div class="mt-4 flex items-center">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $activity->activityType->name }}
                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $activity->creator->name }}
                                </span>
                            </div>

                            <a href="{{ route('activities.show', $activity) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
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
    </script>
</body>

</html>
