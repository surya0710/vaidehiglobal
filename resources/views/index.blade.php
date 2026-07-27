<!doctype html>
<html lang="en">

<head>
    <!-- Google Tag Manager -->
    <script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=GTM-5C73FX45' + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-5C73FX45');
    </script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="canonical" href="https://vaidehiglobal.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Vaidehi Global - Regional Sourcing & Execution Partner across North Indian Production Clusters." />
    <title>Vaidehi Global | Regional Sourcing Partner India</title>
    <script type="module" crossorigin src="{{ asset('vite/assets/index-29c91cae.js') }}"></script>
    <link rel="stylesheet" href="{{  asset('vite/assets/index-5db5be48.css') }}">
    <script type="module">
    window.onerror = (message, source, lineno, colno, errorObj) => {
        const errorDetails = errorObj ? JSON.stringify({
            name: errorObj.name,
            message: errorObj.message,
            stack: errorObj.stack,
            source,
            lineno,
            colno,
        }) : null;

        window.parent.postMessage({
            type: 'horizons-runtime-error',
            message,
            error: errorDetails
        }, '*');
    };
    </script>
    <script type="module">
    const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            for (const addedNode of mutation.addedNodes) {
                if (
                    addedNode.nodeType === Node.ELEMENT_NODE &&
                    (
                        addedNode.tagName?.toLowerCase() === 'vite-error-overlay' ||
                        addedNode.classList?.contains('backdrop')
                    )
                ) {
                    handleViteOverlay(addedNode);
                }
            }
        }
    });

    observer.observe(document.documentElement, {
        childList: true,
        subtree: true
    });

    function handleViteOverlay(node) {
        if (!node.shadowRoot) {
            return;
        }

        const backdrop = node.shadowRoot.querySelector('.backdrop');

        if (backdrop) {
            const overlayHtml = backdrop.outerHTML;
            const parser = new DOMParser();
            const doc = parser.parseFromString(overlayHtml, 'text/html');
            const messageBodyElement = doc.querySelector('.message-body');
            const fileElement = doc.querySelector('.file');
            const messageText = messageBodyElement ? messageBodyElement.textContent.trim() : '';
            const fileText = fileElement ? fileElement.textContent.trim() : '';
            const error = messageText + (fileText ? ' File:' + fileText : '');

            window.parent.postMessage({
                type: 'horizons-vite-error',
                error,
            }, '*');
        }
    }
    </script>
    <script type="module">
    const originalConsoleError = console.error;
    console.error = function(...args) {
        originalConsoleError.apply(console, args);

        let errorString = '';

        for (let i = 0; i < args.length; i++) {
            const arg = args[i];
            if (arg instanceof Error) {
                errorString = arg.stack || `${arg.name}: ${arg.message}`;
                break;
            }
        }

        if (!errorString) {
            errorString = args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' ');
        }

        window.parent.postMessage({
            type: 'horizons-console-error',
            error: errorString
        }, '*');
    };
    </script>
    <script type="module">
    const originalFetch = window.fetch;

    window.fetch = function(...args) {
        const url = args[0] instanceof Request ? args[0].url : args[0];

        // Skip WebSocket URLs
        if (url.startsWith('ws:') || url.startsWith('wss:')) {
            return originalFetch.apply(this, args);
        }

        return originalFetch.apply(this, args)
            .then(async response => {
                const contentType = response.headers.get('Content-Type') || '';

                // Exclude HTML document responses
                const isDocumentResponse =
                    contentType.includes('text/html') ||
                    contentType.includes('application/xhtml+xml');

                if (!response.ok && !isDocumentResponse) {
                    const responseClone = response.clone();
                    const errorFromRes = await responseClone.text();
                    const requestUrl = response.url;
                    console.error(`Fetch error from ${requestUrl}: ${errorFromRes}`);
                }

                return response;
            })
            .catch(error => {
                if (!url.match(/.html?$/i)) {
                    console.error(error);
                }

                throw error;
            });
    };
    </script>
    <script type="module">
    if (window.navigation && window.self !== window.top) {
        window.navigation.addEventListener('navigate', (event) => {
            const url = event.destination.url;

            try {
                const destinationUrl = new URL(url);
                const destinationOrigin = destinationUrl.origin;
                const currentOrigin = window.location.origin;

                if (destinationOrigin === currentOrigin) {
                    return;
                }
            } catch (error) {
                return;
            }

            window.parent.postMessage({
                type: 'horizons-navigation-error',
                url,
            }, '*');
        });
    }
    </script>
    <script type="application/ld+json">
    @verbatim {
        "@context": "https://schema.org",
        "@graph": [{
                "@type": "Organization",
                "@id": "https://www.vaidehiglobal.com/#organization",
                "name": "Vaidehi Global",
                "url": "https://www.vaidehiglobal.com/",
                "logo": {
                    "@type": "ImageObject",
                    "url": "https://www.vaidehiglobal.com/assets/vaidehi-global-logo-213148b0.png"
                },
                "image": "https://www.vaidehiglobal.com/assets/vaidehi-global-logo-213148b0.png",
                "description": "Vaidehi Global is an India-based sourcing and export partner specializing in premium honey, herbal ingredients, spices, natural products, and customized sourcing solutions for importers, wholesalers, distributors, and private label brands worldwide. We help businesses source high-quality products from verified Indian manufacturers with a strong focus on quality assurance, compliance, transparency, and global supply chain excellence.",
                "email": "mailto:info@vaidehiglobal.com",
                "telephone": "+91-9696966964",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "9/5, Mandir Lane, Yusuf Sarai",
                    "addressLocality": "New Delhi",
                    "addressRegion": "Delhi",
                    "postalCode": "110016",
                    "addressCountry": "IN"
                },
                "areaServed": [{
                        "@type": "Country",
                        "name": "United States"
                    },
                    {
                        "@type": "Country",
                        "name": "United Kingdom"
                    },
                    {
                        "@type": "Country",
                        "name": "Australia"
                    },
                    {
                        "@type": "Country",
                        "name": "Canada"
                    },
                    {
                        "@type": "Country",
                        "name": "Germany"
                    },
                    {
                        "@type": "Country",
                        "name": "United Arab Emirates"
                    },
                    {
                        "@type": "Country",
                        "name": "Worldwide"
                    }
                ],
                "knowsAbout": [
                    "Honey Export",
                    "Bulk Honey Supplier",
                    "Himalayan Honey",
                    "Herbal Ingredients",
                    "Natural Products",
                    "Indian Spices",
                    "Private Label Manufacturing",
                    "Contract Manufacturing",
                    "Global Sourcing",
                    "Export Documentation",
                    "Quality Inspection",
                    "Supply Chain Management"
                ]
            },
            {
                "@type": "WebSite",
                "@id": "https://www.vaidehiglobal.com/#website",
                "url": "https://www.vaidehiglobal.com/",
                "name": "Vaidehi Global",
                "publisher": {
                    "@id": "https://www.vaidehiglobal.com/#organization"
                },
                "inLanguage": "en"
            },
            {
                "@type": "WebPage",
                "@id": "https://www.vaidehiglobal.com/#webpage",
                "url": "https://www.vaidehiglobal.com/",
                "name": "Vaidehi Global | Trusted Sourcing & Export Partner from India",
                "isPartOf": {
                    "@id": "https://www.vaidehiglobal.com/#website"
                },
                "about": {
                    "@id": "https://www.vaidehiglobal.com/#organization"
                },
                "description": "Source premium honey, herbal ingredients, spices, natural products, and customized export solutions from India with Vaidehi Global. Trusted sourcing partner for importers, wholesalers, distributors, and private label brands worldwide.",
                "breadcrumb": {
                    "@id": "https://www.vaidehiglobal.com/#breadcrumb"
                },
                "primaryImageOfPage": {
                    "@type": "ImageObject",
                    "url": "https://www.vaidehiglobal.com/assets/vaidehi-global-logo-213148b0.png"
                },
                "inLanguage": "en"
            },
            {
                "@type": "BreadcrumbList",
                "@id": "https://www.vaidehiglobal.com/#breadcrumb",
                "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "https://www.vaidehiglobal.com/"
                }]
            }
        ]
    }
    @endverbatim
    </script>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5C73FX45" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="root"></div>

</body>

</html>