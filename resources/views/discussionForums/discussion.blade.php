<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkandaGo | Discussion Forums</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .chat-sidebar {
            transition: transform 0.3s ease;
        }

        @media (max-width: 1024px) {
            .chat-sidebar {
                transform: translateX(-100%);
            }

            .chat-sidebar.active {
                transform: translateX(0);
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .typing-dot {
            animation: typingDot 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typingDot {

            0%,
            60%,
            100% {
                transform: translateY(0);
                opacity: 0.7
            }

            30% {
                transform: translateY(-10px);
                opacity: 1
            }
        }

        .message-bubble {
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .message-input {
            min-height: 44px;
            max-height: 120px;
            resize: none;
        }

    </style>
</head>

<body class="bg-gray-50">
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar: Daftar Forum -->
        <aside
            class="chat-sidebar fixed lg:static inset-y-0 left-0 z-50 w-80 lg:w-96 bg-white border-r border-gray-200 flex flex-col">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Discussions</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-sm text-gray-600">Connect with classmates</p>
                            <span class="text-gray-300">|</span>
                            <a href="{{ Auth::user()->role == 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}"
                                class="flex items-center gap-1.5 text-sm font-medium text-red-500 hover:text-red-700">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                            </a>
                        </div>
                    </div>
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-600"></i>
                    </button>
                </div>

                @if($isTeacher)
                <button onclick="document.getElementById('createForumModal').classList.remove('hidden')"
                    class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium mb-3">
                    + Create New Forum
                </button>
                @endif

                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text"
                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Search forums...">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="p-4 space-y-6">
                    @if($forums->count() == 0)
                    <div class="text-center py-10">
                        <i data-lucide="message-square" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                        <p class="text-gray-500">No forums yet.</p>
                        @if($isTeacher)
                        <p class="text-sm text-gray-400 mt-2">Create your first forum!</p>
                        @endif
                    </div>
                    @else
                    <div class="space-y-2">
                        @foreach($forums as $f)
                        <div class="chat-item p-3 rounded-xl hover:bg-gray-50 cursor-pointer {{ isset($forum) && $forum->id == $f->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}"
                            onclick="window.location='{{ route('discussionForums.show', $f->id) }}'">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-lg">
                                        {{ Str::upper(Str::substr($f->title, 0, 2)) }}
                                    </div>
                                    <div
                                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-semibold text-gray-800 truncate">{{ $f->title }}</h4>
                                        <span class="text-xs text-gray-500">
                                            {{ $f->comments->count() > 0 ? $f->comments->last()->created_at->format('H:i') : 'New' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate">
                                        {{ $f->comments->last()?->comment ?? 'No messages yet' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span
                                            class="inline-flex items-center text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                            {{ $f->members->count() }} members
                                        </span>
                                    </div>
                                </div>
                                @if($f->teacher_id === Auth::id())
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Admin</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </aside>

        <!-- Main Chat Area -->
        <main class="flex-1 flex flex-col bg-white">
            @if(isset($forum))
            <!-- Header Forum Aktif -->
            <header class="border-b border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
                            <i data-lucide="menu" class="w-5 h-5 text-gray-600"></i>
                        </button>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                {{ Str::upper(Str::substr($forum->title, 0, 2)) }}
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-800">{{ $forum->title }}</h2>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span>{{ $forum->members->count() }} members</span>
                                    <span>•</span>
                                    <span class="flex items-center text-green-600">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                        {{ $forum->members->count() }} online
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($isAdmin) && $isAdmin)
                    <div class="flex gap-2">
                        <button onclick="editForum({{ $forum->id }})" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i data-lucide="edit" class="w-5 h-5 text-gray-600"></i>
                        </button>
                        <form action="{{ route('discussionForums.destroy', $forum->id) }}" method="POST"
                            onsubmit="return confirm('Delete this forum?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 hover:bg-red-50 rounded-lg">
                                <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </header>

            <!-- Messages -->
            <div id="chatContainer" class="flex-1 overflow-y-auto custom-scrollbar bg-gradient-to-b from-gray-50 to-white p-4">
                <div  id="chatMessages" class="max-w-4xl mx-auto space-y-6">
                    @forelse($forum->comments as $comment)
                    <div
                        class="flex items-start gap-3 message-bubble {{ $comment->user_id === Auth::id() ? 'justify-end' : '' }}">
                        @if($comment->user_id !== Auth::id())
                        <div class="w-10 h-10 rounded-full
                                    {{ $comment->user_id === Auth::id()
                                        ? 'bg-gradient-to-br from-blue-500 to-indigo-600'
                                        : 'bg-gradient-to-br from-gray-400 to-gray-600' }}
                                    flex items-center justify-center text-white text-sm font-medium shrink-0">
                            {{ Str::upper(Str::substr($comment->user->name, 0, 2)) }}
                        </div>

                        @endif

                        <div class="flex flex-col {{ $comment->user_id === Auth::id() ? 'items-end text-right' : 'items-start' }}">
                    
                            <div
                                class="flex items-baseline gap-2 mb-1 {{ $comment->user_id === Auth::id() ? 'justify-end' : '' }}">
                                <span
                                    class="font-medium text-sm {{ $comment->user->role === 'teacher' ? 'text-blue-600' : 'text-gray-700' }}">
                                    {{ $comment->user->name }}
                                    @if($comment->user->role === 'teacher') <span
                                        class="text-xs text-blue-500 font-medium">Teacher</span> @endif
                                </span>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->format('H:i') }}</span>
                            </div>
                            <div 
                                class="inline-block max-w-lg px-4 py-3 {{ $comment->user_id === Auth::id() ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl rounded-tr-sm' : 'bg-white border border-gray-200 rounded-2xl rounded-tl-sm' }} shadow-sm">
                                <p>{!! nl2br(e($comment->comment)) !!}</p>
                                @if($comment->picture)
                                <img src="{{ asset('storage/' . $comment->picture) }}" class="mt-3 rounded-lg max-w-xs">
                                @endif
                            </div>
                        </div>

                        @if($comment->user_id === Auth::id())
                        <div 
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-medium">
                            {{ Str::upper(Str::substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-500">
                        No messages yet. Start the discussion!
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Input Comment -->
            <footer class="border-t border-gray-200 bg-white p-4 shadow-lg">
                <form action="{{ route('discussionForums.storeComment', $forum->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="max-w-4xl mx-auto flex items-end gap-3">
                        <label class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg cursor-pointer">
                            <i data-lucide="paperclip" class="w-5 h-5"></i>
                            <input type="file" name="picture" accept="image/*" class="hidden">
                        </label>
                        <textarea name="comment"
                            class="message-input flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Type your message..." rows="1" oninput="autoResize(this)" required></textarea>
                        <button type="submit"
                            class="px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium">
                            <i data-lucide="send" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>
            </footer>

            @else
            <!-- Default: Belum pilih forum -->
            <div class="flex-1 flex items-center justify-center bg-gradient-to-b from-gray-50 to-white">
                <div class="text-center max-w-md px-6">
                    <i data-lucide="message-square" class="w-24 h-24 text-gray-300 mx-auto mb-6"></i>
                    <h3 class="text-2xl font-medium mb-2 text-gray-800">Welcome to Discussion Forums</h3>
                    <p class="text-gray-500">Select a forum from the list to start discussing.</p>
                </div>
            </div>
            @endif
        </main>
    </div>

    <!-- Modal Create Forum (hanya teacher) -->
    @if($isTeacher)
    <div id="createForumModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold mb-4">Create New Forum</h3>
            <form action="{{ route('discussionForums.store') }}" method="POST">
                @csrf
                <input type="text" name="title" placeholder="Forum Title"
                    class="w-full px-4 py-3 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <select name="class_id"
                    class="w-full px-4 py-3 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('createForumModal').classList.add('hidden')"
                        class="flex-1 py-3 border rounded-lg hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            document.querySelector('.chat-sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('hidden');
        }
        document.getElementById('overlay').onclick = toggleSidebar;

        function autoResize(t) {
            t.style.height = 'auto';
            t.style.height = Math.min(t.scrollHeight, 120) + 'px';
        }

        document.querySelectorAll('.chat-item').forEach(item => {
            item.addEventListener('click', function () {
                document.querySelectorAll('.chat-item').forEach(i => i.classList.remove('bg-blue-50',
                    'border-l-4', 'border-blue-500'));
                this.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
                if (window.innerWidth < 1024) toggleSidebar();
            });
        });

    </script>
    <script>
        const forumId = {{ $forum->id ?? 'null' }};
        let lastMessageCount = 0;
    
        function scrollToBottom() {
            const container = document.getElementById('chatContainer');
            container.scrollTop = container.scrollHeight;
        }
    
        function renderMessages(messages) {
            const chat = document.getElementById('chatMessages');
            chat.innerHTML = '';
    
            messages.forEach(msg => {
                const isMe = msg.user_id === {{ Auth::id() }};
    
                chat.innerHTML += `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'} gap-3">
                        ${!isMe ? `
                            <div class="w-10 h-10 rounded-full bg-gray-500 flex items-center justify-center text-white text-sm">
                                ${msg.user.name.substring(0,2).toUpperCase()}
                            </div>
                        ` : ''}
    
                        <div class="max-w-lg ${isMe ? 'text-right' : ''}">
                            <div class="text-xs text-gray-500 mb-1">
                                ${msg.user.name} • ${new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}
                            </div>
                            <div class="px-4 py-3 rounded-2xl shadow
                                ${isMe 
                                    ? 'bg-blue-600 text-white rounded-tr-sm'
                                    : 'bg-white border rounded-tl-sm'}">
                                ${msg.comment.replace(/\n/g, '<br>')}
                            </div>
                        </div>
    
                        ${isMe ? `
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm">
                                {{ Str::upper(Str::substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        ` : ''}
                    </div>
                `;
            });
    
            scrollToBottom();
        }
    
        function fetchMessages() {
            if (!forumId) return;
    
            fetch(`/discussion-forums/${forumId}/comments`)
                .then(res => res.json())
                .then(data => {
                    if (data.length !== lastMessageCount) {
                        lastMessageCount = data.length;
                        renderMessages(data);
                    }
                });
        }
    
        // polling setiap 3 detik
        setInterval(fetchMessages, 3000);
    
        // auto scroll pertama kali
        scrollToBottom();
    </script>
    
</body>

</html>
