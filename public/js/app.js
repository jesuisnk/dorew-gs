var Aside = document.querySelector('aside');
var profileAside = document.querySelector('aside[dorew-id="profile"]');
var mobileNavbarButton = document.getElementById('mobile-navbar-button');
var mobileNavbar = document.querySelector('.mobile-navbar');
var DorewAdsDisplay = document.getElementById('dorew-ads');

document.addEventListener("DOMContentLoaded", function () {
    // kiểm soát bộ lọc
    let orderByElement = document.getElementById('order_by');
    let sortElement = document.getElementById('sort');

    if (orderByElement) {
        orderByElement.addEventListener('change', function () {
            let selectedValue = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('order_by', selectedValue);
            window.location.href = url.toString();
        });
    }

    if (sortElement) {
        sortElement.addEventListener('change', function () {
            let selectedValue = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', selectedValue);
            window.location.href = url.toString();
        });
    }

    // một chút quảng cáo
    var DorewAds = `
    <div class="widget HTML">
        <h2 class="title">Liên kết - bạn bè</h2>
        <div class="widget-content block">
            <ul>
                <li>» <a href="https://chitose2d.blogspot.com/" target="_blank">Chitose2d</a> - Triết lý Anime/Manga/Light Novel</li>
            </ul>
        </div>
    </div>
    `;
    if (DorewAdsDisplay) {
        DorewAdsDisplay.insertAdjacentHTML('beforeend', DorewAds);
    }
});

// Kiểm tra sự tồn tại của phần tử <aside dorew-id="profile">
if (Aside && profileAside) {
    // Nếu phần tử tồn tại, hiển thị #mobile-navbar-button
    mobileNavbarButton.style.display = 'block';
    //setProfileAside();
    if (window.innerWidth <= 738) {
        hideProfileAside();
    }
} else {
    if (mobileNavbarButton) {
        mobileNavbarButton.style.display = 'none';
    }
}
var closeButtonAdded = false;
// Sự kiện resize và khởi tạo
window.addEventListener('resize', function () {
    if (window.innerWidth <= 738) {
        hideProfileAside();
    } else {
        if (profileAside) {
            profileAside.style.display = 'block';
        }
    }
});
// Thêm sự kiện click vào mobileNavbarButton để hiển thị profileAside
if (mobileNavbarButton) {
    mobileNavbarButton.addEventListener('click', function () {
        if (profileAside.style.display === 'none' || profileAside.style.display === '') {
            showProfileAside();
        } else {
            hideProfileAside();
        }
    });
}
// Hàm hiển thị profileAside
function showProfileAside() {
    profileAside.style.display = 'block';
    profileAside.style.position = 'fixed';
    profileAside.style.top = '0';
    profileAside.style.left = '50%';
    profileAside.style.transform = 'translateX(-50%) scale(1)';
    profileAside.style.height = '100%';
    profileAside.style.width = '100%';
    profileAside.style.transition = 'transform 0.3s ease-in-out';
    profileAside.style.backgroundColor = '#ffffff';
    profileAside.style.padding = '10px';
    profileAside.style.overflowY = 'auto';
    profileAside.style.zIndex = '99999999';

    // Thêm nút close vào profileAside nếu chưa được thêm
    if (!closeButtonAdded) {
        var closeButton = document.createElement('button');
        closeButton.className = 'btn btn-block btn-warning';
        closeButton.textContent = 'Close';
        closeButton.style.marginTop = '10px';
        closeButton.addEventListener('click', function () {
            hideProfileAside();
        });
        profileAside.appendChild(closeButton);
        closeButtonAdded = true; // Đánh dấu là đã thêm nút Close
    }

    // Thêm độ trễ để hiển thị animation
    setTimeout(function () {
        profileAside.style.transform = 'translateX(-50%) scale(1, 1)';
    }, 10);
}
// Hàm ẩn profileAside
function hideProfileAside() {
    profileAside.style.transform = 'translateX(-50%) scale(1, 0.01)';
    setTimeout(function () {
        profileAside.style.display = 'none';
        setProfileAside();
    }, 300);
}
// Hàm xóa style showProfileAside() về mặc định
function setProfileAside() {
    profileAside.style.position = '';
    profileAside.style.top = '';
    profileAside.style.left = '';
    profileAside.style.transform = '';
    profileAside.style.height = '';
    profileAside.style.width = '';
    profileAside.style.backgroundColor = '';
    profileAside.style.padding = '';
    profileAside.style.overflowY = '';
    profileAside.style.zIndex = '';

    if (closeButtonAdded) {
        var closeButton = profileAside.querySelector('button.btn.btn-block.btn-warning');
        if (closeButton) {
            profileAside.removeChild(closeButton); // Xóa nút close
        }
        closeButtonAdded = false; // Xóa đánh dấu đã thêm nút Close
    }
}

// giữ cố định sidebar khi trang cuộn
window.addEventListener('scroll', function () {
    var column1 = document.getElementById('post-wrapper');
    var column2 = document.getElementById('sidebar-wrapper');

    if (column1 && column2) {
        // Kiểm tra nếu cột 1 đã trôi xuống
        if (column1.getBoundingClientRect().top < 0) {
            column2.classList.add('add-sticky');
        } else {
            column2.classList.remove('add-sticky');
        }
    }
});

// tag bbcode
function tag(start_tag, end_tag) {
    var oldContent = $("textarea").val();
    var newContent = oldContent.substring(0, $("textarea").get(0).selectionStart) + start_tag + oldContent.substring($("textarea").get(0).selectionStart, $("textarea").get(0).selectionEnd) + end_tag + oldContent.substring($("textarea").get(0).selectionEnd);
    $("textarea").val(newContent);
}
function show_hide(e) {
    obj = document.getElementById(e), "none" == obj.style.display ? obj.style.display = "block" : obj.style.display = "none"
}