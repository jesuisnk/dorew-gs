function imgur(f, ob) {
    var files = document.querySelector(f); files.onchange = function () {
        var file = this.files[0]; if (file && file.type.match(/image.*/)) {
            var fd = new FormData(); fd.append("image", file); var xhr = new XMLHttpRequest(); xhr.open("POST", "https://api.imgur.com/3/image.json"); xhr.upload.onprogress = function (e) { if (e.lengthComputable) { var percent = Math.floor((e.loaded / e.total) * 100) + '%'; ob.loading(percent) } }; xhr.onload = function () { var imgs; var res = JSON.parse(xhr.responseText); if (res.status === 200 && res.success === !0) { var data = res.data; ob.loaded(data.link, data.type, data.size, data.datetime) } else { window.alert('Lỗi: tải lên thất bại') } }
            xhr.setRequestHeader('Authorization', 'Client-ID b558035630d26ef'); xhr.send(fd)
        } else { window.alert('Chỉ cho phép chọn ảnh') }
    }
}