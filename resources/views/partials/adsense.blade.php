@php($qjAd = \App\Support\WebAds::placement($placement ?? ''))
@if($qjAd['enabled'])
    <aside class="qj-ad-slot" aria-label="Advertisement">
        <span class="qj-ad-slot__label">Advertisement</span>
        @if($qjAd['has_ad_unit'])
            <ins class="adsbygoogle qj-ad-slot__unit"
                 style="display:block"
                 data-ad-client="{{ $qjAd['publisher_id'] }}"
                 data-ad-slot="{{ $qjAd['slot_id'] }}"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>(window.adsbygoogle = window.adsbygoogle || []).push({});</script>
        @elseif($qjAd['test_mode'])
            <div class="qj-ad-slot__preview"
                 data-qj-ad-test-id="{{ $qjAd['test_id'] }}"
                 data-qj-ad-provider="local-preview"
                 aria-label="Local ad placement preview; no Google ad request is made">
                <span class="qj-ad-slot__preview-badge">TEST PREVIEW</span>
                <div class="qj-ad-slot__preview-content">
                    <p class="qj-ad-slot__preview-title">Website ad placement</p>
                    <p class="qj-ad-slot__preview-copy">Responsive slot preview only. No live Google ad is requested.</p>
                    <code class="qj-ad-slot__preview-id">{{ $qjAd['test_id'] }}</code>
                </div>
            </div>
        @else
            <div class="qj-ad-slot__house">
                <span class="qj-ad-slot__mark" aria-hidden="true">Q</span>
                <div>
                    <p class="qj-ad-slot__title">Reach Papua New Guinea’s job seekers.</p>
                    <p class="qj-ad-slot__copy">Promote your company or opportunity to people looking for their next move.</p>
                </div>
                <a href="{{ route('advertising') }}" class="qj-ad-slot__link">Advertise with QJobs</a>
            </div>
        @endif
    </aside>
@endif
