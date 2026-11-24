@php($adsensePublisherId = \App\Support\WebAds::publisherId())
@if(\App\Support\WebAds::enabled() && \App\Support\WebAds::liveAdsAllowed() && $adsensePublisherId !== '')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ urlencode($adsensePublisherId) }}" crossorigin="anonymous"></script>
@endif
