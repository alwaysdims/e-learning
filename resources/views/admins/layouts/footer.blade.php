<!-- BEGIN: JS Assets-->
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=[" your-google-map-api"]&libraries=places"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('Enigma/Compiled') }}/dist/js/app.js"></script>
<!-- END: JS Assets-->
<!-- Code injected by live-server -->
<script>
    // <![CDATA[  <-- For SVG support
    if ('WebSocket' in window) {
        (function () {
            function refreshCSS() {
                var sheets = [].slice.call(document.getElementsByTagName("link"));
                var head = document.getElementsByTagName("head")[0];
                for (var i = 0; i < sheets.length; ++i) {
                    var elem = sheets[i];
                    var parent = elem.parentElement || head;
                    parent.removeChild(elem);
                    var rel = elem.rel;
                    if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() ==
                        "stylesheet") {
                        var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
                        elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date()
                            .valueOf());
                    }
                    parent.appendChild(elem);
                }
            }
            var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
            var address = protocol + window.location.host + window.location.pathname + '/ws';
            var socket = new WebSocket(address);
            socket.onmessage = function (msg) {
                if (msg.data == 'reload') window.location.reload();
                else if (msg.data == 'refreshcss') refreshCSS();
            };
            if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
                console.log('Live reload enabled.');
                sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
            }
        })();
    } else {
        console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    }
    // ]]>

</script>


<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Logout?',
            text: 'Kamu akan keluar dari akun ini',
            icon: 'warning',
            showCancelButton: true,

            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',

            buttonsStyling: false,
            customClass: {
                popup: 'rounded-xl bg-slate-800 shadow-xl',
                title: 'text-lg font-semibold text-black',
                htmlContainer: 'text-black',
                confirmButton: 'btn btn-danger px-6 mr-2',
                cancelButton: 'btn btn-outline-secondary px-6'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-xl bg-white text-slate-800 shadow-xl',
            title: 'text-lg font-semibold',
            htmlContainer: 'text-slate-600'
        }
    });

</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session('error') }}',
        timer: 2500,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-xl bg-white text-slate-800 shadow-xl',
            title: 'text-lg font-semibold',
            htmlContainer: 'text-slate-600'
        }
    });
</script>
@endif
