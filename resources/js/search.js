//script untuk pencarian global
document.addEventListener('DOMContentLoaded', function () {
    
    const searchInput = document.getElementById('default-search');
    const resultsContainer = document.getElementById('search-results');
    let debounceTimer;

    //Helper function untuk format tanggal
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    if (searchInput && resultsContainer) {
        
        searchInput.addEventListener('input', function (e) {
            const query = e.target.value;
            clearTimeout(debounceTimer);
            if (query.length < 1) { 
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
                return;
            }

            // Set timer debounce baru (300ms)
            debounceTimer = setTimeout(() => {
                // menampilkan spinner loading
                resultsContainer.innerHTML = `
                    <div class="flex items-center justify-center p-4">
                        <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="ml-2 text-sm text-gray-500">Mencari...</span>
                    </div>
                `;
                resultsContainer.classList.remove('hidden');

                // Kirim request fetch
                fetch(`/search?query=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const userRole = data.user_role;
                    renderResults(data, userRole); 
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                    resultsContainer.innerHTML = '<div class="p-3 text-gray-500 text-sm">Gagal memuat hasil.</div>';
                    resultsContainer.classList.remove('hidden');
                });
            }, 300); 
        });

        // sembunyikan hasil jika klik di luar
        document.addEventListener('click', function (e) {
            const searchWrapper = searchInput.closest('.relative');
            if (!searchWrapper.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    }

    function renderResults(data, userRole) {
        resultsContainer.innerHTML = ''; 
        if ((!data.rapat || !data.rapat.length) && (!data.absensis || !data.absensis.length) && (!data.notulensi || !data.notulensi.length)) {
            resultsContainer.innerHTML = '<div class="p-3 text-gray-500 text-sm">Tidak ada hasil ditemukan.</div>';
            resultsContainer.classList.remove('hidden');
            return;
        }

        let html = '';

        //Bagian Rapat
        if (data.rapat && data.rapat.length > 0) {
            html += '<div class="border-b">';
            html += '<strong class="block text-base font-extrabold px-3 pt-2 pb-1 text-gray-900 uppercase">Rapat</strong>';
            data.rapat.forEach(item => {
                
                let statusBadge = '';
                let statusColor = '';

                switch (item.status) {
                    case 'selesai':
                        statusColor = 'text-gray-700 bg-gray-100';
                        break;
                    case 'sedang berlangsung':
                        statusColor = 'text-green-700 bg-green-100';
                        break;
                    case 'terjadwal':
                        statusColor = 'text-blue-700 bg-blue-100';
                        break;
                    case 'dibatalkan':
                        statusColor = 'text-red-700 bg-red-100';
                        break;
                    default:
                        statusColor = 'text-gray-700 bg-gray-100';
                }
                
                statusBadge = `<span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full ${statusColor}">${item.status}</span>`;
        

                const link = `/rapat/${item.id}`; 
                html += `
                    <a href="${link}" class="block p-3 hover:bg-gray-100">
                        <div class="font-medium text-base text-gray-900">${item.judul} 
                            ${statusBadge}
                        </div>
                        <div class="text-sm text-gray-600">${formatDate(item.tanggal)} | ${item.lokasi} | ${item.waktu_mulai.substring(0, 5)} - ${item.waktu_selesai.substring(0, 5)}</div>
                    </a>
                `;
            });
            html += '</div>';
        }

        if (data.absensis && data.absensis.length > 0) {
            html += '<div class="border-b">';
            html += '<strong class="block text-base font-extrabold px-3 pt-2 pb-1 text-gray-900 uppercase">Absensi</strong>';
            
            data.absensis.forEach(item => {
                let kehadiranBadge = '';
                if (userRole === 'pegawai' && item.absensis && item.absensis.length > 0) { 
                    const status = item.absensis[0].kehadiran; 
                    const color = status === 'hadir' ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100';
                    kehadiranBadge = `<span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full ${color}">${status}</span>`;
                }
                
                let linkTag, closeLinkTag;
                
                if (userRole === 'pegawai') {
                    linkTag = `<div class="block p-3 cursor-default">`;
                    closeLinkTag = `</div>`;
                } else {
                    linkTag = `<a href="/rapat/${item.id}" class="block p-3 hover:bg-gray-100">`;
                    closeLinkTag = `</a>`;
                }
                
                html += `
                    ${linkTag}
                        <div class="font-medium text-base text-gray-900">${item.judul} ${kehadiranBadge}</div>
                        <div class="text-sm text-gray-600">${formatDate(item.tanggal)} | ${item.lokasi} | ${item.waktu_mulai.substring(0, 5)} - ${item.waktu_selesai.substring(0, 5)}</div>
                    ${closeLinkTag}
                `;
            });
            html += '</div>';
        }

        if (data.notulensi && data.notulensi.length > 0) {
            html += '<div>';
            html += '<strong class="block text-base font-extrabold px-3 pt-2 pb-1 text-gray-900 uppercase">Notulensi</strong>';
            data.notulensi.forEach(item => {
                const link = `/rapat/${item.id}`; 
                html += `
                    <a href="${link}" class="block p-3 hover:bg-gray-100">
                        <div class="font-medium text-base text-gray-900">${item.judul}</div>
                        <div class="text-sm text-gray-600">${formatDate(item.tanggal)} | ${item.lokasi} | ${item.waktu_mulai.substring(0, 5)} - ${item.waktu_selesai.substring(0, 5)}</div>
                    </a>
                `;
            });
            html += '</div>';
        }

        resultsContainer.innerHTML = html;
        resultsContainer.classList.remove('hidden');
    }
});