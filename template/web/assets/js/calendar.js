document.addEventListener('DOMContentLoaded', function() {
    const calendarContainer = document.getElementById('mhb-calendar-container');
    if (!calendarContainer) return;

    const prevBtn = document.getElementById('mhb-cal-prev');
    const nextBtn = document.getElementById('mhb-cal-next');
    const datesValueDisplay = document.querySelector('#mhb-dates-field .mhb-value span');
    const datesLabelDisplay = document.querySelector('#mhb-dates-field .mhb-label');

    // Today is March 22, 2026
    const today = new Date(2026, 2, 22); 
    today.setHours(0, 0, 0, 0);

    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    let startDate = null;
    let endDate = null;

    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    // Also get mobile calendar container
    const mobCalContainer = document.getElementById('mob-cal-specific-wrap');

    function renderCalendar() {
        calendarContainer.innerHTML = '';
        if (mobCalContainer) mobCalContainer.innerHTML = '';
        
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

        // Also render into mobile container
        if (mobCalContainer) {
            const mobMonthDiv = monthDiv.cloneNode(false); // empty clone (no children yet)
            mobMonthDiv.appendChild(header.cloneNode(true));
            // Re-build the grid for mobile so click events are fresh
            const mobGrid = document.createElement('div');
            mobGrid.className = 'mhb-days-grid';
            // Add day name headers
            dayNames.forEach(name => {
                const n = document.createElement('div');
                n.className = 'mhb-day-name';
                n.textContent = name;
                mobGrid.appendChild(n);
            });
            // Prev month trailing days
            for (let i = firstDay - 1; i >= 0; i--) {
                const d = document.createElement('div');
                d.className = 'mhb-day other-month';
                d.textContent = prevMonthLastDay - i;
                mobGrid.appendChild(d);
            }
            // Current month days
            for (let i = 1; i <= daysInMonth; i++) {
                const dateObj = new Date(year, month, i);
                const d = document.createElement('div');
                d.className = 'mhb-day';
                d.textContent = i;
                if (dateObj < today) {
                    d.classList.add('disabled');
                } else {
                    d.addEventListener('click', () => handleDateClick(dateObj));
                }
                if (startDate && dateObj.getTime() === startDate.getTime()) {
                    d.classList.add(endDate ? 'selected-start' : 'selected');
                } else if (endDate && dateObj.getTime() === endDate.getTime()) {
                    d.classList.add('selected-end');
                } else if (startDate && endDate && dateObj > startDate && dateObj < endDate) {
                    d.classList.add('in-range');
                }
                if (startDate && endDate && dateObj >= startDate && dateObj <= endDate) {
                    const dow = dateObj.getDay();
                    if (dow === 0) d.classList.add('range-row-start');
                    if (dow === 6) d.classList.add('range-row-end');
                }
                mobGrid.appendChild(d);
            }
            // Next month leading days
            const filled = firstDay + daysInMonth;
            const remaining = filled % 7 === 0 ? 0 : 7 - (filled % 7);
            for (let i = 1; i <= remaining; i++) {
                const d = document.createElement('div');
                d.className = 'mhb-day other-month';
                d.textContent = i;
                mobGrid.appendChild(d);
            }
            mobMonthDiv.appendChild(mobGrid);
            mobCalContainer.appendChild(mobMonthDiv);
        }

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

        // Mobile display elements
        const mobBookDatesVal = document.querySelector('.m-mob-book-dates-val');
        const mobCalDates = document.querySelector('.m-mob-cal-dates');
        const mobBookLabel = document.querySelector('.m-mob-book-label');
        const mobCalLabel = document.querySelector('.m-mob-cal-label');
        const mobCalDone = document.getElementById('m-mob-calendar-done');

        if (typeof isFlexible !== 'undefined' && isFlexible) {
            const targetDate = new Date(today.getFullYear(), today.getMonth() + flexSelectedMonthIdx, 1);
            const monthStr = monthNames[targetDate.getMonth()];
            const yearStr = targetDate.getFullYear();
            const nightText = `(${flexNights} NIGHT${flexNights > 1 ? 'S' : ''})`;
            
            datesValueDisplay.innerHTML = `Flexible In ${monthStr} ${yearStr}`;
            datesLabelDisplay.innerHTML = `<i class="bi bi-calendar3" style="font-size: 14px; margin-right: 5px;"></i> DATES ${nightText}`;

            if (mobBookDatesVal) mobBookDatesVal.innerHTML = `<span>Flexible In</span><span>${monthStr} ${yearStr}</span>`;
            if (mobCalDates) mobCalDates.innerHTML = `<span class="m-mob-cal-start">Flexible In</span><span class="m-mob-cal-end">${monthStr} ${yearStr}</span>`;
            if (mobBookLabel) mobBookLabel.innerHTML = `<i class="bi bi-calendar3"></i> DATES ${nightText}`;
            if (mobCalLabel) mobCalLabel.innerHTML = `<i class="bi bi-calendar3"></i> DATES ${nightText}`;
            if (mobCalDone) mobCalDone.textContent = "Check Availability";
            
            return;
        }

        if (startDate && endDate) {
            const options = { weekday: 'short', month: 'short', day: '2-digit' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', options);
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const nightText = `(${diffDays} NIGHT${diffDays > 1 ? 'S' : ''})`;
            
            datesValueDisplay.innerHTML = `${startStr} <span style="margin: 0 10px; color: #767676;">&rarr;</span> ${endStr}`;
            datesLabelDisplay.innerHTML = `<i class="bi bi-calendar3" style="font-size: 14px; margin-right: 5px;"></i> DATES ${nightText}`;

            // Update mobile displays
            if (mobBookDatesVal) {
                mobBookDatesVal.innerHTML = `<span>${startStr}</span><span>${endStr}</span>`;
            }
            if (mobCalDates) {
                mobCalDates.innerHTML = `<span class="m-mob-cal-start">${startStr}</span><span class="m-mob-cal-end">${endStr}</span>`;
            }
            if (mobBookLabel) mobBookLabel.innerHTML = `<i class="bi bi-calendar3"></i> DATES ${nightText}`;
            if (mobCalLabel) mobCalLabel.innerHTML = `<i class="bi bi-calendar3"></i> DATES ${nightText}`;
            if (mobCalDone) mobCalDone.textContent = "View Rates";
        } else if (startDate) {
            const options = { weekday: 'short', month: 'short', day: '2-digit' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            
            datesValueDisplay.innerHTML = `${startStr} <span style="margin: 0 10px; color: #767676;">&rarr;</span> SELECT END DATE`;
            
            if (mobBookDatesVal) {
                mobBookDatesVal.innerHTML = `<span>${startStr}</span><span>SELECT END</span>`;
            }
            if (mobCalDates) {
                mobCalDates.innerHTML = `<span class="m-mob-cal-start">${startStr}</span><span class="m-mob-cal-end">SELECT END</span>`;
            }
            if (mobCalDone) mobCalDone.textContent = "View Rates";
        } else {
            if (mobCalDone) mobCalDone.textContent = "View Rates";
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

    // =====================================
    // Flexible Dates Logic
    // =====================================
    const dateTabs = document.querySelectorAll('.mhb-date-tab');
    const flexContainer = document.getElementById('mhb-flexible-dates-container');
    const flexNightsCount = document.getElementById('flex-nights-count');
    const flexMinusBtn = flexContainer ? flexContainer.querySelector('.minus') : null;
    const flexPlusBtn = flexContainer ? flexContainer.querySelector('.plus') : null;
    const flexMonthsGrid = document.getElementById('flex-months-grid');

    window.isFlexible = false;
    window.flexNights = 1;
    window.flexSelectedMonthIdx = 0;

    dateTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            dateTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            if (tab.dataset.tab === 'flexible') {
                isFlexible = true;
                if(calendarContainer) calendarContainer.classList.add('d-none');
                if(flexContainer) flexContainer.classList.remove('d-none');
                if(flexMonthsGrid && flexMonthsGrid.children.length === 0) renderFlexMonths();
                updateDisplay();
            } else {
                isFlexible = false;
                if(calendarContainer) calendarContainer.classList.remove('d-none');
                if(flexContainer) flexContainer.classList.add('d-none');
                updateDisplay();
            }
        });
    });

    if (flexMinusBtn && flexPlusBtn && flexNightsCount) {
        flexPlusBtn.addEventListener('click', () => {
            if (flexNights < 30) flexNights++;
            updateFlexNightsDisplay();
        });
        flexMinusBtn.addEventListener('click', () => {
            if (flexNights > 1) flexNights--;
            updateFlexNightsDisplay();
        });
    }

    function updateFlexNightsDisplay() {
        if(flexNightsCount) flexNightsCount.textContent = flexNights;
        if(flexMinusBtn) flexMinusBtn.classList.toggle('disabled', flexNights <= 1);
        updateDisplay();
    }

    function renderFlexMonths() {
        if(!flexMonthsGrid) return;
        flexMonthsGrid.innerHTML = '';
        for (let i = 0; i < 12; i++) {
            const tempDate = new Date(today.getFullYear(), today.getMonth() + i, 1);
            const btn = document.createElement('button');
            btn.className = 'mhb-month-pill' + (i === flexSelectedMonthIdx ? ' active' : '');
            btn.textContent = `${monthNames[tempDate.getMonth()]} ${tempDate.getFullYear()}`;
            btn.addEventListener('click', () => {
                flexSelectedMonthIdx = i;
                renderFlexMonths(); // re-render to update active class
                updateDisplay();
            });
            flexMonthsGrid.appendChild(btn);
        }
    }

    renderCalendar();
});
