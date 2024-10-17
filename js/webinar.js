// js/webinar.js
document.addEventListener('DOMContentLoaded', function () {
    // بارگذاری داده‌ها از فایل JSON
    fetch('webinars.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const webinars = data.webinars;
            const webinarDetails = []; // آرایه‌ای برای ذخیره اطلاعات وبینارها

            // برای هر اسلاگ، تاریخ شروع و اطلاعات دیگر را بارگذاری کنیم
            const fetchPromises = webinars.map(slug => {
                return fetch(`https://api.eseminar.tv/api/v1/webinar/${slug}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            const webinar = data.data.webinar;
                            const startAt = new Date(webinar.start_at);
                            
                            // فقط وبینارهایی که تاریخ آن‌ها آینده است را اضافه کنیم
                            if (startAt > new Date()) {
                                webinarDetails.push({
                                    slug: slug,
                                    title: webinar.title,
                                    description: webinar.description,
                                    startAt: startAt,
                                    cover: webinar.cover
                                });
                            }
                        }
                    });
            });

            // منتظر ماندن تا همه درخواست‌ها به اتمام برسند
            Promise.all(fetchPromises).then(() => {
                // مرتب‌سازی وبینارها بر اساس تاریخ شروع
                webinarDetails.sort((a, b) => a.startAt - b.startAt);

                // نمایش وبینارها
                const listContainer = document.getElementById('webinarList');
                webinarDetails.forEach(item => {
                    const card = document.createElement('div');
                    card.className = 'col-md-4 col-sm-6'; // ریسپانسیو
                    card.innerHTML = `
                        <div class="webinar-card card">
                            <img src="${item.cover}" alt="${item.title}">
                            <div class="card-body">
                                <h5 class="card-title">${item.title}</h5>
                                <p class="card-text">${item.description}</p>
                                <p><strong>تاریخ شروع:</strong> ${item.startAt.toLocaleString('fa-IR')}</p>
                                <a class="btn btn-primary" href="https://eseminar.tv/webinar/${item.slug}" target="_blank">ثبت نام در وبینار</a>
                            </div>
                        </div>
                    `;
                    listContainer.appendChild(card);
                });
            });
        })
        .catch(error => {
            console.error('Error fetching webinars:', error);
            alert('خطا در بارگذاری وبینارها.');
        });

    // تابع برای پنهان کردن و نمایان کردن هدر
    let lastScrollTop = 0;
    const header = document.getElementById("header");
    
    window.addEventListener("scroll", function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop) {
            // Scroll Down
            header.style.top = "-80px"; // ارتفاع هدر را باید به مقدار منفی بدهید
        } else {
            // Scroll Up
            header.style.top = "0";
        }
        lastScrollTop = scrollTop;
    });
});
