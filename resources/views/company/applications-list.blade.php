@if($applications->count() > 0)
    @php $statuses = \App\Models\BiraConsultantApplicationStatus::orderBy('id')->get(); @endphp
    @foreach($applications as $application)
    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md" id="application-{{ $application->id }}">
        <div class="p-5">
            <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                <!-- Applicant Info -->
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="flex-shrink-0">
                        @if($application->user && $application->user->image)
                            <img src="{{ asset('storage/' . $application->user->image) }}" alt="" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-lg font-bold text-gray-500 dark:text-gray-400">{{ substr(optional($application->user)->name ?? '?', 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-semibold text-black dark:text-white">{{ optional($application->user)->name ?? 'Unknown' }}</h3>
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $application->status->color_class ?? 'bg-gray-100 text-gray-700' }}" id="status-badge-{{ $application->id }}">
                                {{ $application->status->label ?? 'Unknown' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ optional($application->user)->email ?? 'No email' }}</p>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <span>Applied for: <a href="{{ route('job.detail', $application->job->slug) }}" class="text-yellow-500 hover:text-yellow-600 font-medium">{{ $application->job->title ?? 'Unknown' }}</a></span>
                            <span>•</span>
                            <span>{{ $application->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                        @if(optional($application->user)->country)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">📍 {{ $application->user->country->name }}</p>
                        @endif
                    </div>
                </div>

                <!-- Stage Update -->
                <div class="flex-shrink-0 lg:w-56">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Update Stage</label>
                    <select onchange="updateStage({{ $application->id }}, this.value)"
                            class="w-full px-3 py-2 rounded-full text-sm focus:ring-2 focus:ring-yellow-300 bg-gray-100 text-black dark:bg-black dark:text-white">
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ $application->status_id == $status->id ? 'selected' : '' }}>{{ $status->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Cover Letter Preview -->
            @if($application->cover_letter)
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <button onclick="toggleCoverLetter({{ $application->id }})" class="text-xs text-yellow-500 hover:text-yellow-600 font-medium focus:outline-none">
                    <span id="cl-toggle-{{ $application->id }}">Show Cover Letter ▾</span>
                </button>
                <div id="cl-content-{{ $application->id }}" class="hidden mt-2">
                    <div class="bg-gray-50 dark:bg-black rounded-md p-3 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line max-h-40 overflow-y-auto">
                        {{ $application->cover_letter }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Attached Files -->
            @if($application->cv_path || $application->resume_path || $application->cover_letter_path)
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Attached Files:</p>
                <div class="flex flex-wrap gap-2">
                    @if($application->cv_path)
                        <a href="{{ route('company.application.download', [$application, 'cv']) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-full text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            CV
                        </a>
                    @endif
                    @if($application->resume_path)
                        <a href="{{ route('company.application.download', [$application, 'resume']) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-full text-xs font-medium hover:bg-green-100 dark:hover:bg-green-900/40 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Resume
                        </a>
                    @endif
                    @if($application->cover_letter_path)
                        <a href="{{ route('company.application.download', [$application, 'cover_letter']) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-medium hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Cover Letter
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
@else
    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <h3 class="text-lg font-semibold text-black dark:text-white mb-2">No applications found</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Applications will appear here when candidates apply to your job postings.</p>
    </div>
@endif