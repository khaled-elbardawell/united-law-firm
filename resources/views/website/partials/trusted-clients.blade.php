@if (isset($clients) && $clients->isNotEmpty())
    <section class="section trusted-clients-section" id="trustedClients">
        <div class="container">
            <div class="sec-head">
                <div class="kicker">ثقة وشراكات</div>
                <h2>من الجهات التي وثقت بخدماتنا القانونية</h2>
                <p>نفخر بثقة جهات وأعمال اختارت خبرتنا القانونية لحماية مصالحها وبناء قرارات أكثر اطمئنانا.</p>
            </div>

            <div class="trusted-slider" data-trusted-slider>
                <button class="trusted-slider-btn trusted-slider-btn--prev" type="button" aria-label="السابق">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <div class="trusted-slider-track">
                    @foreach ($clients as $client)
                        @php
                            $clientLogo = $client->logo
                                ? (str_starts_with($client->logo, 'http') ? $client->logo : asset($client->logo))
                                : null;
                        @endphp

                        @if ($client->website_url)
                            <a class="trusted-client-card" href="{{ $client->website_url }}" target="_blank" rel="noopener">
                        @else
                            <article class="trusted-client-card">
                        @endif
                            <span class="trusted-client-logo">
                                @if ($clientLogo)
                                    <img src="{{ $clientLogo }}" alt="{{ $client->name }}">
                                @else
                                    <i class="fa-solid fa-building-columns"></i>
                                @endif
                            </span>
                            <span class="trusted-client-content">
                                <strong>{{ $client->name }}</strong>
                                @if ($client->description)
                                    <small>{{ $client->description }}</small>
                                @endif
                            </span>
                        @if ($client->website_url)
                            </a>
                        @else
                            </article>
                        @endif
                    @endforeach
                </div>

                <button class="trusted-slider-btn trusted-slider-btn--next" type="button" aria-label="التالي">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            </div>
        </div>
    </section>
@endif
