import './bootstrap';

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

$(function () {
    $(document).on('click', '.p-search__box--btn', function () {
        $('.p-list tbody').empty(); //もともとある要素を空にする
        $('.search-null').remove(); //検索結果が0のときのテキストを消す
        $('.pagination').empty();
        $('.p-list__sort').empty();

        let keyword = $('#keyword').val(); //検索ワードを取得
        let company = $('#company').val();
        let lower_price = $('#lower_price').val();
        let upper_price = $('#upper_price').val();
        let lower_stock = $('#lower_stock').val();
        let upper_stock = $('#upper_stock').val();

        $.ajax({
            type: 'GET',
            url: 'search',
            async: false,
            data: {
                keyword: keyword,
                company: company,
                lower_price: lower_price,
                upper_price: upper_price,
                lower_stock: lower_stock,
                upper_stock: upper_stock
            },
            dataType: 'json', //json形式で受け取る
        }).done(function (data) {
            let res = data.products.data;
            $.each(res, function (index, value) {
                let id = value.id;
                let img_path = value.img_path;
                let product_name = value.product_name;
                let price = value.price;
                let stock = value.stock;
                let company_name = value.company.company_name;
                let html = '<tr>';
                html += `<td>${id}</td>`;
                if (img_path) {
                    html += `<td><img src="http://localhost/sales/public/storage/images/${img_path}"></td>`;
                    }else{
                    html += '<td><p class="m-0">商品画像</p></td>';
                };
                html += `<td>${product_name}</td>`;
                html += `<td>￥${price}</td>`;
                html += `<td>${stock}</td>`;
                html += `<td>${company_name}</td>`;
                let detail_form = `<form action="detail/${id}" method="GET">`;
                detail_form += '<button type="submit" class="btn btn-info">詳細</button>';
                detail_form += '</form>';
                html += `<td class="p-list__detail">${detail_form}</td>`;
                html += `<td class="p-list__remove"><button type="submit" id=${id} class="btn btn-danger">削除</button></td>`;
                $('.p-list tbody').append(html);
            })

            let current_page = data.products.current_page;
            let next_page_url = data.products.next_page_url;
            let prev_page_url = data.products.prev_page_url;
            let last_page = data.products.last_page;

            //ページネーター描画
            //Prev 制御
            if (prev_page_url == null) {
                $(".pagination").append(
                    "<li class='page-item disabled' aria-disabled='true' aria-label='« Previous'><span class='page-link' aria-hidden='true'>‹</span></li>")
            } else {
                $(".pagination").append(
                    `<li class='page-item'><button class='page-link' href='${prev_page_url}' rel='prev' aria-label='« Previous'>‹</button></li>`);
            }

            //ページリンク
            for(var i=0;i<last_page;i++) {
                var link_page = i+1;

                //activeにするかどうか
                if(current_page==link_page)
                {
                    $(".pagination").append(
                        `<li class='page-item active'><span class='page-link'>${link_page}</span></li>`);
                }else{
                    $(".pagination").append(
                        `<li class='page-item'><button class='page-link' href='https://localhost/sales/public/search?page=${link_page}'>${link_page}</button></li>`);
                }
            }

            //Next制御
            if(next_page_url == null){
                $(".pagination").append(
                    "<li class='page-item disabled' aria-disabled='true' aria-label='Next »'><span class='page-link' aria-hidden='true'>›</span></li>");
            }else{
                $(".pagination").append(
                    `<li class='page-item'><button class='page-link' href='${next_page_url}' rel='next' aria-label='Next »'>›</button></li>`);
            }

        }).fail(function () {
            //ajax通信がエラーのときの処理
            console.log('通信に失敗しました。');
        })
    })
})

// ページネーションリンクがクリックされたときの処理
$(function () {
    var sort = '';
    var direction = 'desc';
    $(document).on('click', '.sort p', function () {
        sort = $(this).attr('name');
        if (direction === 'asc'){
            direction = 'desc';
        }else{
            direction = 'asc';
        }
    });
    $(document).on('click', '.page-item button', function () {
        console.log(sort);
        $('.p-list tbody').empty();
        $('.pagination').empty();

        let page = $(this).attr('href').split('page=')[1];
        // 現在の検索クエリを取得
        let keyword = $('#keyword').val();
        let company = $('#company').val();
        let lower_price = $('#lower_price').val();
        let upper_price = $('#upper_price').val();
        let lower_stock = $('#lower_stock').val();
        let upper_stock = $('#upper_stock').val();
    $.ajax({
        type: 'GET',
        url: 'search?page=' + page,
        async: false,
        data: {
            keyword: keyword,
            company: company,
            lower_price: lower_price,
            upper_price: upper_price,
            lower_stock: lower_stock,
            upper_stock: upper_stock,
            'sort': sort,
            'direction': direction
        },
        dataType: 'json',
    })
    .done(function (data) {
        let res = data.products.data;
        $.each(res, function (index, value) {
            let id = value.id;
            let img_path = value.img_path;
            let product_name = value.product_name;
            let price = value.price;
            let stock = value.stock;
            let company_name = value.company.company_name;
            let html = '<tr>';
            html += `<td>${id}</td>`;
            if (img_path) {
                html += `<td><img src="http://localhost/sales/public/storage/images/${img_path}"></td>`;
            } else {
                html += '<td><p class="m-0">商品画像</p></td>';
            };
            html += `<td>${product_name}</td>`;
            html += `<td>￥${price}</td>`;
            html += `<td>${stock}</td>`;
            html += `<td>${company_name}</td>`;
            let detail_form = `<form action="detail/${id}" method="GET">`;
            detail_form += '<button type="submit" class="btn btn-info">詳細</button>';
            detail_form += '</form>';
            html += `<td class="p-list__detail">${detail_form}</td>`;
            html += `<td class="p-list__remove"><button type="submit" id=${id} class="btn btn-danger">削除</button></td>`;
            $('.p-list tbody').append(html);
        })
        let current_page = data.products.current_page;
        let next_page_url = data.products.next_page_url;
        let prev_page_url = data.products.prev_page_url;
        let last_page = data.products.last_page;

        //ページネーター描画
        //Prev 制御
        if (prev_page_url == null) {
            $(".pagination").append(
                "<li class='page-item disabled' aria-disabled='true' aria-label='« Previous'><span class='page-link' aria-hidden='true'>‹</span></li>")
        } else {
            $(".pagination").append(
                `<li class='page-item'><button class='page-link' href='${prev_page_url}' rel='prev' aria-label='« Previous'>‹</button></li>`);
        }

        //ページリンク
        for(var i=0;i<last_page;i++)
        {
            var link_page = i+1;

            //activeにするかどうか
            if(current_page==link_page)
            {
                $(".pagination").append(
                    `<li class='page-item active'><span class='page-link'>${link_page}</span></li>`);
            }else{
                $(".pagination").append(
                    `<li class='page-item'><button class='page-link' href='https://localhost/sales/public/search?page=${link_page}'>${link_page}</button></li>`);
            }
        }

        //Next制御
        if(next_page_url == null){
            $(".pagination").append(
                "<li class='page-item disabled' aria-disabled='true' aria-label='Next »'><span class='page-link' aria-hidden='true'>›</span></li>");
        }else{
            $(".pagination").append(
                `<li class='page-item'><button class='page-link' href='${next_page_url}' rel='next' aria-label='Next »'>›</button></li>`);
        }

        }).fail(function () {
            alert('エラーが発生しました。');
        });
    });
})

$(function () {
    var sort = '';
    var direction = 'desc';
    $(document).on('click', '.sort p', function () {
        $('.p-list tbody').empty();
        $('.pagination').empty();
        $('.p-list__sort').empty();
        $('#keyword').val('');
        $('#company').val('');
        $('#lower_price').val('');
        $('#upper_price').val('');
        $('#lower_stock').val('');
        $('#upper_stock').val('');

        sort = $(this).attr('name');
        if (direction === 'asc'){
            direction = 'desc';
        }else{
            direction = 'asc';
        };

        if (direction == 'desc') {
            if (sort == 'id') {
                $('.p-list__sort').append(`<p>ソート条件：id 並び順：降順</p>`);
            } else if (sort == 'img_path') {
                $('.p-list__sort').append(`<p>ソート条件：商品画像 並び順：降順</p>`);
            } else if (sort == 'product_name') {
                $('.p-list__sort').append(`<p>ソート条件：商品名 並び順：降順</p>`);
            } else if (sort == 'price') {
                $('.p-list__sort').append(`<p>ソート条件：価格 並び順：降順</p>`);
            } else if (sort == 'stock') {
                $('.p-list__sort').append(`<p>ソート条件：在庫数 並び順：降順</p>`);
            } else if (sort == 'company_name'){
                $('.p-list__sort').append(`<p>ソート条件：メーカー名 並び順：降順</p>`);
            } else {
                $('.p-list__sort').append(`<p>ソート条件： 並び順：</p>`);
            }
        } else {
            if (sort == 'id') {
                $('.p-list__sort').append(`<p>ソート条件：id 並び順：昇順</p>`);
            } else if (sort == 'img_path') {
                $('.p-list__sort').append(`<p>ソート条件：商品画像 並び順：昇順</p>`);
            } else if (sort == 'product_name') {
                $('.p-list__sort').append(`<p>ソート条件：商品名 並び順：昇順</p>`);
            } else if (sort == 'price') {
                $('.p-list__sort').append(`<p>ソート条件：価格 並び順：昇順</p>`);
            } else if (sort == 'stock') {
                $('.p-list__sort').append(`<p>ソート条件：在庫数 並び順：昇順</p>`);
            } else if (sort == 'company_name') {
                $('.p-list__sort').append(`<p>ソート条件：メーカー名 並び順：昇順</p>`);
            } else {
                $('.p-list__sort').append(`<p>ソート条件： 並び順：</p>`);
            }
        };
        $.ajax({
            url: 'sort?page=1',
            type: 'GET',
            data: {
                'sort': sort,
                'direction': direction
            },
            dataType: 'json',
        }).done(function (data) {
            let res = data.products.data;
            $.each(res, function (index, value) {
                let id = value.id;
                let img_path = value.img_path;
                let product_name = value.product_name;
                let price = value.price;
                let stock = value.stock;
                let company_name = value.company.company_name;
                let html = '<tr>';
                html += `<td>${id}</td>`;
                if (img_path) {
                    html += `<td><img src="http://localhost/sales/public/storage/images/${img_path}"></td>`;
                } else {
                    html += '<td><p class="m-0">商品画像</p></td>';
                };
                html += `<td>${product_name}</td>`;
                html += `<td>￥${price}</td>`;
                html += `<td>${stock}</td>`;
                html += `<td>${company_name}</td>`;
                let detail_form = `<form action="detail/${id}" method="GET">`;
                detail_form += '<button type="submit" class="btn btn-info">詳細</button>';
                detail_form += '</form>';
                html += `<td class="p-list__detail">${detail_form}</td>`;
                html += `<td class="p-list__remove"><button type="submit" id=${id} class="btn btn-danger">削除</button></td>`;
                $('.p-list tbody').append(html);
            })
            let current_page = data.products.current_page;
            let next_page_url = data.products.next_page_url;
            let prev_page_url = data.products.prev_page_url;
            let last_page = data.products.last_page;

            //ページネーター描画
            //Prev 制御
            if (prev_page_url == null) {
                $(".pagination").append(
                    "<li class='page-item disabled' aria-disabled='true' aria-label='« Previous'><span class='page-link' aria-hidden='true'>‹</span></li>")
            } else {
                $(".pagination").append(
                    `<li class='page-item'><button class='page-link' href='${prev_page_url}' rel='prev' aria-label='« Previous'>‹</button></li>`);
            }

            //ページリンク
            for(var i=0;i<last_page;i++)
            {
                var link_page = i+1;

                //activeにするかどうか
                if(current_page==link_page)
                {
                    $(".pagination").append(
                        `<li class='page-item active'><span class='page-link'>${link_page}</span></li>`);
                }else{
                    $(".pagination").append(
                        `<li class='page-item'><button class='page-link' href='https://localhost/sales/public/search?page=${link_page}'>${link_page}</button></li>`);
                }
            }

            //Next制御
            if(next_page_url == null){
                $(".pagination").append(
                    "<li class='page-item disabled' aria-disabled='true' aria-label='Next »'><span class='page-link' aria-hidden='true'>›</span></li>");
            }else{
                $(".pagination").append(
                    `<li class='page-item'><button class='page-link' href='${next_page_url}' rel='next' aria-label='Next »'>›</button></li>`);
            }
        }).fail(function () {
            //ajax通信がエラーのときの処理
            console.log('通信に失敗しました。');
        });
    })
})

$(function () {
    $(document).on('click', '.p-list__remove button', function () {
        let deleteConfirm = confirm('本当に削除しますか？');

        if (deleteConfirm == true) {
            let clickElement = $(this);
            let userId = clickElement.attr('id');

            $.ajax({
                url: 'delete/' + userId,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'id': userId,
                    '_method': 'DELETE'
                }
            }).done(function (data) {
                clickElement.parents('tr').remove();
            }).fail(function () {
                //ajax通信がエラーのときの処理
                console.log('通信に失敗しました。');
            });
        } else {
            (function (e) {
                e.preventDefault()
            });
        }
    })
})
