@extends('admin.app')

@section('content')
<div class="p-4 md:p-6 max-w-4xl">
    <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-yellow-600 hover:underline">← Back to contact messages</a>
    <div class="mt-4 rounded-lg bg-white dark:bg-custom-dark shadow p-5 md:p-7">
        <div class="flex flex-wrap justify-between gap-3 mb-5">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $contactMessage->subject }}</h1>
            <span class="text-xs text-gray-500">{{ $contactMessage->created_at->format('d M Y, H:i') }}</span>
        </div>
        <dl class="grid gap-3 sm:grid-cols-2 text-sm mb-6">
            <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $contactMessage->name }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd><a class="text-yellow-600 hover:underline" href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></dd></div>
        </dl>
        <div class="border-t dark:border-gray-700 pt-5 text-gray-800 dark:text-gray-100 whitespace-pre-line">{{ $contactMessage->message }}</div>
        <div class="mt-6"><a class="inline-block rounded-full bg-black text-white px-5 py-2" href="mailto:{{ $contactMessage->email }}?subject={{ rawurlencode('Re: ' . $contactMessage->subject) }}">Reply by email</a></div>
    </div>
</div>
@endsection
