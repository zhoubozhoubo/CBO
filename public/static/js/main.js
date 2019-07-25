// 改变分页时
function changePage() {
    $('.page ul').html('')
    let str = ''
    if (page !== 1) {
        str += '<li class="first_page cursor" onclick="prePage()"></li>'
    }
    if (pages <= 10) {
        for (let i = 1; i <= pages; i++) {
            if (page === i) {
                str += '<li class="cursor action" onclick="jumpPage(' + i + ')">' + i + '</li>'
            } else {
                str += '<li class="cursor" onclick="jumpPage(' + i + ')">' + i + '</li>'
            }
        }
    }
    if (pages > 10) {
        if (page <= 5) {
            for (let i = 1; i <= 9; i++) {
                if (page === i) {
                    str += '<li class="cursor action" onclick="jumpPage(' + i + ')">' + i + '</li>'
                } else {
                    str += '<li class="cursor" onclick="jumpPage(' + i + ')">' + i + '</li>'
                }
            }
            str += '<li>...</li>'
        }
        if (page > 5 && page < pages - 5) {
            str += '<li>...</li>'
            for (let i = page - 4; i <= page + 4; i++) {
                if (page === i) {
                    str += '<li class="cursor action" onclick="jumpPage(' + i + ')">' + i + '</li>'
                } else {
                    str += '<li class="cursor" onclick="jumpPage(' + i + ')">' + i + '</li>'
                }
            }
            str += '<li>...</li>'
        }
        if (page >= pages - 5) {
            str += '<li>...</li>'
            for (let i = pages - 8; i <= pages; i++) {
                if (page === i) {
                    str += '<li class="cursor action" onclick="jumpPage(' + i + ')">' + i + '</li>'
                } else {
                    str += '<li class="cursor" onclick="jumpPage(' + i + ')">' + i + '</li>'
                }
            }
        }
    }
    if (page !== pages) {
        str += '<li class="last_page cursor" onclick="nextPage()"></li>'
    }
    $('.page ul').html(str)
}

// 跳转到指定页
function jumpPage(toPage) {
    page = toPage
    getList()
}

// 上一页
function prePage() {
    page--
    getList()
}

// 下一页
function nextPage() {
    page++
    getList()
}

//跳转到table_bar
function gotoTableBar() {
    // document.getElementById("table_bar").scrollIntoView()
    $("html,body").animate({scrollTop: $("#table_bar").offset().top}, 500)
}












