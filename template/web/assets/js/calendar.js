document.addEventListener('DOMContentLoaded', function() {
    const calendarContainer = document.getElementById('mhb-calendar-container');
    if (!calendarContainer) return;

    const prevBtn = document.getElementById('mhb-cal-prev');
    const nextBtn = document.getElementById('mhb-cal-next');
    const datesValueDisplay = document.querySelector('#mhb-dates-field .mhb-value span');
    const datesLabelDisplay = document.querySelector('#mhb-dates-field .mhb-label');

    // Today is March 19, 2026
    const today = new Date(2026, 2, 19); 
    today.setHours(0, 0, 0, 0);

    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    let startDate = null;
    let endDate = null;

    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    function renderCalendar() {
        calendarContainer.innerHTML = '';
        
        renderMonth(currentYear, currentMonth);
        
        let nextMonth = currentMonth + 1;
        let nextYear = currentYear;
        if (nextMonth > 11) {
            nextMonth = 0;
            nextYear++;
        }
        renderMonth(nextYear, nextMonth);

        // Update nav buttons visibility/state
        const isCurrentMonth = currentYear === today.getFullYear() && currentMonth === today.getMonth();
        if (prevBtn) {
            if (isCurrentMonth) {
                prevBtn.style.opacity = '0.3';
                prevBtn.style.pointerEvents = 'none';
            } else {
                prevBtn.style.opacity = '1';
                prevBtn.style.pointerEvents = 'auto';
            }
        }
    }

    function renderMonth(year, month) {
        const monthDiv = document.createElement('div');
        monthDiv.className = 'mhb-calendar-month';

        const header = document.createElement('div');
        header.className = 'mhb-month-header';
        
        const title = document.createElement('div');
        title.className = 'mhb-month-title';
        title.textContent = `${monthNames[month]} ${year}`;
        
        const prevPlaceholder = document.createElement('div');
        const nextPlaceholder = document.createElement('div');
        
        header.appendChild(prevPlaceholder);
        header.appendChild(title);
        header.appendChild(nextPlaceholder);
        
        monthDiv.appendChild(header);

        const grid = document.createElement('div');
        grid.className = 'mhb-days-grid';

        dayNames.forEach(name => {
            const nameDiv = document.createElement('div');
            nameDiv.className = 'mhb-day-name';
            nameDiv.textContent = name;
            grid.appendChild(nameDiv);
        });

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const prevMonthLastDay = new Date(year, month, 0).getDate();
        for (let i = firstDay - 1; i >= 0; i--) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'mhb-day other-month';
            dayDiv.textContent = prevMonthLastDay - i;
            grid.appendChild(dayDiv);
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const dateObj = new Date(year, month, i);
            const dayDiv = document.createElement('div');
            dayDiv.className = 'mhb-day';
            dayDiv.textContent = i;

            if (dateObj < today) {
                dayDiv.classList.add('disabled');
            } else {
                dayDiv.addEventListener('click', () => handleDateClick(dateObj));
            }

            // Highlight logic
            if (startDate && dateObj.getTime() === startDate.getTime()) {
                if (endDate) {
                    dayDiv.classList.add('selected-start');
                } else {
                    dayDiv.classList.add('selected');
                }
            } else if (endDate && dateObj.getTime() === endDate.getTime()) {
                dayDiv.classList.add('selected-end');
            } else if (startDate && endDate && dateObj > startDate && dateObj < endDate) {
                dayDiv.classList.add('in-range');
            }

            // Row-based rounding for range
            if (startDate && endDate) {
                const dayOfWeek = dateObj.getDay();
                if (dateObj >= startDate && dateObj <= endDate) {
                    if (dayOfWeek === 0) dayDiv.classList.add('range-row-start');
                    if (dayOfWeek === 6) dayDiv.classList.add('range-row-end');
                }
            }

            grid.appendChild(dayDiv);
        }

        const remainingCells = 42 - (grid.children.length - 7);
        for (let i = 1; i <= remainingCells; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'mhb-day other-month';
            dayDiv.textContent = i;
            grid.appendChild(dayDiv);
        }

        monthDiv.appendChild(grid);
        calendarContainer.appendChild(monthDiv);

        if (month === currentMonth && year === currentYear && prevBtn) {
            prevPlaceholder.replaceWith(prevBtn);
        }
        
        let nextM = currentMonth + 1;
        let nextY = currentYear;
        if (nextM > 11) { nextM = 0; nextY++; }
        if (month === nextM && year === nextY && nextBtn) {
            nextPlaceholder.replaceWith(nextBtn);
        }
    }

    function handleDateClick(date) {
        if (!startDate || (startDate && endDate)) {
            startDate = date;
            endDate = null;
        } else if (startDate && !endDate) {
            if (date < startDate) {
                startDate = date;
            } else if (date.getTime() === startDate.getTime()) {
                startDate = null;
            } else {
                endDate = date;
            }
        }
        renderCalendar();
        updateDisplay();
    }

    function updateDisplay() {
        if (!datesValueDisplay || !datesLabelDisplay) return;

        if (startDate && endDate) {
            const options = { weekday: 'short', month: 'short', day: '2-digit' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', options);
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            datesValueDisplay.innerHTML = `${startStr} <span style="margin: 0 10px; color: #767676;">&rarr;</span> ${endStr}`;
            datesLabelDisplay.innerHTML = `<i class="bi bi-calendar3" style="font-size: 14px; margin-right: 5px;"></i> DATES (${diffDays} NIGHT${diffDays > 1 ? 'S' : ''})`;
        } else if (startDate) {
            const options = { weekday: 'short', month: 'short', day: '2-digit' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            datesValueDisplay.innerHTML = `${startStr} <span style="margin: 0 10px; color: #767676;">&rarr;</span> SELECT END DATE`;
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });
    }

    renderCalendar();
});
