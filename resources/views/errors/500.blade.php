@extends('app')

@section('content')
<section class="flex items-center h-full p-16 bg-white text-gray-900">
	<div class="container flex flex-col items-center justify-center mx-auto my-8">
		<div class="max-w-md text-center">
			<img src="{{ URL::asset('images/500.png') }}" alt="500 Error" class="mx-auto" />
			<p class="text-3xl font-merienda font-medium md:text-3xl mt-6">We're working on it.</p>
			<p class="mt-4 mb-8 font-medium text-gray-800">But don't worry, go back to our homepage.</p>
			<a href="{{ URL::to('/') }}" class="btn text-white bg-black hover:bg-lime-500 shadow-lg shadow-black/50 hover:shadow-lime-500/50 font-medium rounded-full font-nunito text-lg px-4 py-2 text-center ml-4 mr-4 mb-2">Homepage</a>
		</div>
	</div>
</section>
@endsection
