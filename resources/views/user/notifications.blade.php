@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="bc-dashboard-page">
    <div class="bc-dashboard-hero">
        <div class="bc-dashboard-hero__inner">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Notifications</h1>
                    <p class="opacity-80 mt-1">{{ $unreadCount }} unread notification{{ $unreadCount != 1 ? 's' : '' }}</p>
                </div>
                @if($unreadCount > 0)
                    <button id="markAllReadBtn"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        Mark All as Read
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            @if($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $type = $data['type'] ?? '';
                            $isUnread = is_null($notification->read_at);
                        @endphp
                        <div class="bg-white dark:bg-custom-dark rounded-lg shadow-sm p-4 transition {{ $isUnread ? 'border-l-4 border-yellow-400' : '' }}"
                             data-notification-id="{{ $notification->id }}">
                            <div class="flex items-start gap-4">
                                {{-- Icon --}}
                                <div class="flex-shrink-0">
                                    @if($type === 'company_followed')
                                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    @elseif($type === 'new_job_followed_company')
                                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-black dark:text-white {{ $isUnread ? 'font-semibold' : '' }}">
                                        {{ $data['message'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                    {{-- Action Link --}}
                                    @if($type === 'company_followed' && isset($data['company_id']))
                                        <a href="{{ route('company.detail', $data['company_slug']) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            View Company →
                                        </a>
                                    @elseif($type === 'new_job_followed_company' && isset($data['job_id']))
                                        <a href="{{ route('job.detail', $data['job_slug']) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            View Job →
                                        </a>
                                    @endif
                                </div>

                                {{-- Mark Read --}}
                                @if($isUnread)
                                    <button class="mark-read-btn flex-shrink-0 text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                            data-id="{{ $notification->id }}">
                                        ✓ Read
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No notifications yet</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">
                        Follow companies to get notified about new job postings!
                    </p>
                    <a href="{{ route('companies') }}" class="inline-block mt-4 bg-yellow-300 hover:bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm font-medium transition">
                        Browse Companies
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark single notification as read
    document.querySelectorAll('.mark-read-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var el = this.closest('[data-notification-id]');
            fetch('/user/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            }).then(function(r) { return r.json(); }).then(function() {
                el.classList.remove('border-l-4', 'border-yellow-400');
                var textEl = el.querySelector('.font-semibold');
                if (textEl) textEl.classList.remove('font-semibold');
                btn.remove();
            });
        });
    });

    // Mark all as read
    var markAllBtn = document.getElementById('markAllReadBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch('/user/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            }).then(function(r) { return r.json(); }).then(function() {
                document.querySelectorAll('[data-notification-id]').forEach(function(el) {
                    el.classList.remove('border-l-4', 'border-yellow-400');
                    var textEl = el.querySelector('.font-semibold');
                    if (textEl) textEl.classList.remove('font-semibold');
                });
                document.querySelectorAll('.mark-read-btn').forEach(function(b) { b.remove(); });
                markAllBtn.remove();
            });
        });
    }
});
</script>
@endsection
