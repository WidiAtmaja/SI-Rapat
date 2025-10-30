//script kalender rapat
class KalenderRapat {
    constructor() {
        this.currentDate = new Date();
        this.currentView = 'month';
        this.rapatData = window.rapatData || [];
        console.log('Kalender initialized with data:', this.rapatData);
        this.init();
    }

    init() {
        this.setupEventListeners();
        setTimeout(() => {
            this.renderCalendar();
        }, 100);
    }

    setupEventListeners() {
        // Navigasi tombol
        document.getElementById('todayBtn')?.addEventListener('click', () => {
            this.currentDate = new Date();
            this.renderCalendar();
        });

        document.getElementById('prevBtn')?.addEventListener('click', () => {
            this.navigateCalendar(-1);
        });

        document.getElementById('nextBtn')?.addEventListener('click', () => {
            this.navigateCalendar(1);
        });

        // tombol melihat
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.changeView(e.target.dataset.view);
            });
        });

        // modal tutup
        document.getElementById('closeModal')?.addEventListener('click', () => {
            this.closeModal();
        });

        document.getElementById('detailRapatModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'detailRapatModal') {
                this.closeModal();
            }
        });
    }

    navigateCalendar(direction) {
        if (this.currentView === 'month') {
            this.currentDate.setMonth(this.currentDate.getMonth() + direction);
        } else if (this.currentView === 'week') {
            this.currentDate.setDate(this.currentDate.getDate() + (direction * 7));
        } else if (this.currentView === 'day') {
            this.currentDate.setDate(this.currentDate.getDate() + direction);
        }
        this.renderCalendar();
    }

    changeView(view) {
        this.currentView = view;
        //state update tombol 
        document.querySelectorAll('.view-btn').forEach(btn => {
            if (btn.dataset.view === view) {
                btn.classList.remove('bg-gray-100');
                btn.classList.add('bg-white');
            } else {
                btn.classList.remove('bg-white');
                btn.classList.add('bg-gray-100');
            }
        });

        this.renderCalendar();
    }

    renderCalendar() {
        this.updateMonthYearDisplay();
        
        if (this.currentView === 'month') {
            this.renderMonthView();
        } else if (this.currentView === 'week') {
            this.renderWeekView();
        } else if (this.currentView === 'day') {
            this.renderDayView();
        }
    }

    updateMonthYearDisplay() {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const monthYear = `${months[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
        
        const element = document.getElementById('currentMonthYear');
        if (element) {
            element.textContent = monthYear;
        }
    }

    //render bulanan
    renderMonthView() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const prevLastDay = new Date(year, month, 0);
        
        const firstDayIndex = firstDay.getDay();
        const lastDayDate = lastDay.getDate();
        const prevLastDayDate = prevLastDay.getDate();
        
        let html = `
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="grid grid-cols-7 divide-x divide-gray-200 border-b border-gray-200 bg-gray-50">
                    ${this.renderDayHeaders()}
                </div>
                <div class="grid grid-cols-7 divide-x divide-y divide-gray-200">
        `;
        
        // bulan hari sebelumnya
        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const day = prevLastDayDate - i;
            html += this.renderDayCell(day, true, new Date(year, month - 1, day));
        }
        
        // bulan hari sekarang
        for (let day = 1; day <= lastDayDate; day++) {
            const currentDate = new Date(year, month, day);
            html += this.renderDayCell(day, false, currentDate);
        }
        
        // bulan hari selanjutnya
        const totalCells = firstDayIndex + lastDayDate;
        const remainingCells = totalCells <= 35 ? 35 - totalCells : 42 - totalCells;
        for (let day = 1; day <= remainingCells; day++) {
            html += this.renderDayCell(day, true, new Date(year, month + 1, day));
        }
        
        html += `
                </div>
            </div>
        `;
        
        const container = document.getElementById('calendarContainer');
        if (container) {
            container.innerHTML = html;
        }
    }

    //render mingguan
    renderWeekView() {
        const startOfWeek = new Date(this.currentDate);
        startOfWeek.setDate(this.currentDate.getDate() - this.currentDate.getDay());
        
        let html = `
            <div class="border border-gray-200">
                <div class="grid grid-cols-7 divide-x divide-gray-200 border-b border-gray-200">
        `;
        
        for (let i = 0; i < 7; i++) {
            const day = new Date(startOfWeek);
            day.setDate(startOfWeek.getDate() + i);
            const dayNames = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
            
            html += `
                <div class="p-3.5 flex flex-col sm:flex-row items-center justify-between border-r border-gray-200 last:border-r-0">
                    <span class="text-sm font-medium text-gray-500">${dayNames[i]}</span>
                </div>
            `;
        }
        
        html += `
                </div>
                <div class="grid grid-cols-7 divide-x divide-gray-200">
        `;
        
        for (let i = 0; i < 7; i++) {
            const day = new Date(startOfWeek);
            day.setDate(startOfWeek.getDate() + i);
            html += this.renderDayCell(day.getDate(), false, day, true);
        }
        
        html += `
                </div>
            </div>
        `;
        
        document.getElementById('calendarContainer').innerHTML = html;
    }

    //render harian
    renderDayView() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const day = this.currentDate.getDate();
        const currentDate = new Date(year, month, day);
        
        const rapatToday = this.getRapatForDate(currentDate);
        
        let html = `
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4">${this.formatDate(currentDate)}</h3>
        `;
        
        if (rapatToday.length === 0) {
            html += `<p class="text-gray-500 text-center py-8">Tidak ada rapat pada hari ini</p>`;
        } else {
            rapatToday.forEach(rapat => {
                const statusConfig = this.getStatusConfig(rapat.status);
                html += `
                    <div class="mb-4 p-4 border-l-4 ${statusConfig.borderColor} bg-gray-50 rounded-lg hover:shadow-md transition-all cursor-pointer"
                         onclick="kalenderRapat.showRapatDetail(${rapat.id})">
                        <div class="flex items-start gap-3">
                            ${statusConfig.icon}
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">${rapat.judul}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-medium">Waktu:</span>
                                    ${this.formatIndonesianTime(rapat.waktu_mulai)} - ${this.formatIndonesianTime(rapat.waktu_selesai)}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Lokasi:</span> ${rapat.lokasi}
                                </p>
                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold ${statusConfig.bgColor} ${statusConfig.textColor}">
                                    ${rapat.status.charAt(0).toUpperCase() + rapat.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        html += `</div>`;
        document.getElementById('calendarContainer').innerHTML = html;
    }

    //render header harian
    renderDayHeaders() {
        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        return days.map((day, index) => `
            <div class="p-4 text-center">
                <span class="text-sm font-semibold text-gray-700 uppercase">${day}</span>
            </div>
        `).join('');
    }

    //render harian sell
    renderDayCell(day, isOtherMonth, date, isWeekView = false) {
        const today = new Date();
        const isToday = date.getDate() === today.getDate() && 
                        date.getMonth() === today.getMonth() && 
                        date.getFullYear() === today.getFullYear();
        
        const rapatForDay = this.getRapatForDate(date);
        
        const bgClass = isOtherMonth ? 'bg-gray-50' : 'bg-white';
        
        const dayNumberBaseClass = 'text-base font-semibold flex items-center justify-center w-8 h-8 rounded-full transition-all';
        let dayColorClass = '';
        if (isToday && !isOtherMonth) {
            dayColorClass = 'bg-indigo-600 text-white shadow-md';
        } else if (isOtherMonth) {
            dayColorClass = 'text-gray-400';
        } else {
            dayColorClass = 'text-gray-800';
        }
        const dayNumberClass = `${dayNumberBaseClass} ${dayColorClass}`;

        let rapatHtml = '';
        if (rapatForDay.length > 0 && !isOtherMonth) {
            
            rapatForDay.forEach(rapat => {
                const statusConfig = this.getStatusConfig(rapat.status);

                let meetingIcon = statusConfig.icon;
                if (rapat.link_zoom) {
                    meetingIcon = `<svg class="w-4 h-4 ${statusConfig.textColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>`;
                }

                rapatHtml += `
                    <div class="cursor-pointer p-1.5 rounded ${statusConfig.bgColor} hover:opacity-80 transition-all"
                         onclick="event.stopPropagation(); kalenderRapat.showRapatDetail(${rapat.id})"
                         title="${this.getRapatTooltip(rapat)}">
                         <div class="flex items-center gap-1.5 mt-1">
                            ${meetingIcon}
                            <span class="text-xs font-medium ${statusConfig.textColor}">
                                ${rapat.status.charAt(0).toUpperCase() + rapat.status.slice(1)}
                            </span>
                        </div>
                        
                        <span class="font-medium text-gray-700 break-words text-sm">${rapat.judul}</span>
                        <p class="text-xs text-gray-600 break-words">${this.formatIndonesianTime(rapat.waktu_mulai)} - ${this.formatIndonesianTime(rapat.waktu_selesai)}</p>
                        
                        
                    </div>
                `;
            });
        }
        
        return `
            <div class="p-2 ${bgClass} aspect-square flex flex-col transition-all duration-300 hover:bg-indigo-50 cursor-pointer"
                 onclick="kalenderRapat.handleDayClick(${day}, ${date.getMonth()}, ${date.getFullYear()})">
                
                <div class="flex items-center gap-2">
                    <span class="${dayNumberClass}">
                        ${day}
                    </span>
                    ${(isToday && !isOtherMonth) ? '<span class="text-xs font-medium text-gray-500">Hari Ini</span>' : ''}
                </div>

                <div class="flex-1 overflow-y-auto mt-1 space-y-1.5 p-1 simple-scrollbar">
                    ${rapatHtml}
                </div>
            </div>
        `;
    }

    getRapatForDate(date) {
        const dateStr = this.formatDateForComparison(date);
        
        return this.rapatData.filter(rapat => {
            if (!rapat.tanggal) return false;
            let rapatDate = rapat.tanggal;

            if (typeof rapatDate === 'string') {
                rapatDate = new Date(rapatDate);
            }
            
            const rapatDateStr = this.formatDateForComparison(rapatDate);
            return rapatDateStr === dateStr;
        });
    }

    formatDateForComparison(date) {
        if (!(date instanceof Date)) {
            date = new Date(date);
        }
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    formatDate(date) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    //format waktu indonesia
    formatIndonesianTime(dateTimeString) {
        if (!dateTimeString) return 'N/A';
        
        try {
            const date = new Date(dateTimeString);
            if (isNaN(date.getTime())) {
                if (typeof dateTimeString === 'string' && dateTimeString.match(/^\d{2}:\d{2}(:\d{2})?$/)) {
                    return dateTimeString.substring(0, 5);
                }
                return dateTimeString;
            }
            
            return date.toLocaleTimeString('id-ID', {
                timeZone: 'Asia/Makassar',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        } catch (e) {
            console.error('Error parsing time:', dateTimeString, e);
            return dateTimeString;
        }
    }

    getStatusConfig(status) {
        const configs = {
            'terjadwal': {
                bgColor: 'bg-white',
                textColor: 'text-blue-700',
                borderColor: 'border-blue-500',
                mobileDot: 'bg-blue-500',
                icon: `<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                </svg>`
            },
            'sedang berlangsung': {
                bgColor: 'bg-white',
                textColor: 'text-green-700',
                borderColor: 'border-green-500',
                mobileDot: 'bg-green-500',
                icon: `<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                </svg>`
            },
            'selesai': {
                bgColor: 'bg-white',
                textColor: 'text-gray-700',
                borderColor: 'border-gray-500',
                mobileDot: 'bg-gray-500',
                icon: `<svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                </svg>`
            },
            'dibatalkan': {
                bgColor: 'bg-white',
                textColor: 'text-red-700',
                borderColor: 'border-red-500',
                mobileDot: 'bg-red-500',
                icon: `<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                </svg>`
            }
        };
        
        return configs[status] || configs['terjadwal'];
    }

    getRapatTooltip(rapat) {
        return `${rapat.judul}\nLokasi: ${rapat.lokasi}\nTanggal: ${this.formatDate(new Date(rapat.tanggal))}\nWaktu: ${this.formatIndonesianTime(rapat.waktu_mulai)} - ${this.formatIndonesianTime(rapat.waktu_selesai)}\nStatus: ${rapat.status}`;
    }

    handleDayClick(day, month, year) {
        const clickedDate = new Date(year, month, day);
        const rapatForDay = this.getRapatForDate(clickedDate);
        
        if (rapatForDay.length === 1) {
            this.showRapatDetail(rapatForDay[0].id);
        } else if (rapatForDay.length > 1) {
            this.showMultipleRapatModal(rapatForDay);
        }
    }

    showMultipleRapatModal(rapatList) {
        let html = `
            <div class="space-y-4">
                <h3 class="text-lg font-semibold mb-4">Rapat pada tanggal ini:</h3>
        `;
        
        rapatList.forEach(rapat => {
            const statusConfig = this.getStatusConfig(rapat.status);
            html += `
                <div class="p-4 border ${statusConfig.borderColor} border-l-4 rounded-lg hover:shadow-md transition-all cursor-pointer"
                     onclick="kalenderRapat.showRapatDetail(${rapat.id})">
                    <div class="flex items-start gap-3">
                        ${statusConfig.icon}
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${rapat.judul}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                ${this.formatIndonesianTime(rapat.waktu_mulai)} - ${this.formatIndonesianTime(rapat.waktu_selesai)}
                            </p>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold ${statusConfig.bgColor} ${statusConfig.textColor}">
                                ${rapat.status.charAt(0).toUpperCase() + rapat.status.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `</div>`;
        
        document.getElementById('modalContent').innerHTML = html;
        document.getElementById('detailRapatModal').classList.remove('hidden');
    }

    //menampilan detial rapat
    showRapatDetail(rapatId) {
        const rapat = this.rapatData.find(r => r.id === rapatId);
        if (!rapat) return;
        
        const statusConfig = this.getStatusConfig(rapat.status);
        
        let html = `
            <div class="space-y-6">
                <!-- Header -->
                <div class="border-l-4 ${statusConfig.borderColor} pl-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">${rapat.judul}</h3>
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold ${statusConfig.bgColor} ${statusConfig.textColor}">
                        ${rapat.status.charAt(0).toUpperCase() + rapat.status.slice(1)}
                    </span>
                </div>

                <!-- Details Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Tanggal & Waktu -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Tanggal</p>
                                <p class="text-gray-900">${this.formatDate(new Date(rapat.tanggal))}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Waktu -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Waktu</p>
                                <p class="text-gray-900">${this.formatIndonesianTime(rapat.waktu_mulai)} - ${this.formatIndonesianTime(rapat.waktu_selesai)} WITA</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Lokasi</p>
                                <p class="text-gray-900">${rapat.lokasi}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Perangkat Daerah -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Perangkat Daerah</p>
                                <p class="text-gray-900">${rapat.nama_perangkat_daerah}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PIC -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Person In Charge (PIC)</p>
                            <p class="text-gray-900">${rapat.pic ? rapat.pic.name : 'Tidak ada'}</p>
                        </div>
                    </div>
                </div>

                ${rapat.link_zoom ? `
                <!-- Link Zoom -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-700 mb-1">Link Zoom Meeting</p>
                            <a href="${rapat.link_zoom}" target="_blank" class="text-blue-600 hover:text-blue-800 underline break-all">
                                ${rapat.link_zoom}
                            </a>
                        </div>
                    </div>
                </div>
                ` : ''}

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <a href="${rapat.link_zoom}" target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-center">
                        Bergabung
                    </a>
                  
                </div>
            </div>
        `;
        
        document.getElementById('modalContent').innerHTML = html;
        document.getElementById('detailRapatModal').classList.remove('hidden');
    }

    closeModal() {
        document.getElementById('detailRapatModal').classList.add('hidden');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM loaded, initializing calendar...');
        window.kalenderRapat = new KalenderRapat();
    });
} else {
    console.log('DOM already loaded, initializing calendar...');
    window.kalenderRapat = new KalenderRapat();
}