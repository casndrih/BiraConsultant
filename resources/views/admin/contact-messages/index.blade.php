@extends('admin.app')

@section('content')
<div class="p-4 md:p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Contact messages</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Messages submitted through the public contact form.</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg bg-white dark:bg-custom-dark shadow">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200 qj-admin-data-table qj-admin-data-table--messages">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-black">
                <tr>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Sender</th>
                    <th class="px-5 py-4">Subject</th>
                    <th class="px-5 py-4">Received</th>
                    <th class="px-5 py-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-5 py-4">{{ $message->read_at ? 'Read' : 'Unread' }}</td>
                        <td class="px-5 py-4">
                            <div class="font-medium">{{ $message->name }}</div>
                            <div class="text-xs text-gray-500">{{ $message->email }}</div>
                        </td>
                        <td class="px-5 py-4">{{ $message->subject }}</td>
                        <td class="px-5 py-4">{{ $message->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-4"><a class="font-medium text-yellow-600 hover:underline" href="{{ route('admin.contact-messages.show', $message) }}">View message</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">No contact messages received yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $messages->links() }}</div>
</div>
@endsection
