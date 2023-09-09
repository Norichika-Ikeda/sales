import './bootstrap';

$(function () {
    $('.p-search__box--btn').on('click', function () {
        $('.p-list tbody').empty(); //もともとある要素を空にする
        $('.search-null').remove(); //検索結果が0のときのテキストを消す
        $('.pagination').empty();

        let keyword = $('#keyword').val(); //検索ワードを取得
        let company = $('#company').val();
        let lower_price = $('#lower_price').val();
        let upper_price = $('#upper_price').val();
        let lower_stock = $('#lower_stock').val();
        let upper_stock = $('#upper_stock').val();

        if (!keyword && !company && !lower_price && !upper_price && !lower_stock && !upper_stock) {
            return false;
        } //ガード節で検索ワードが空の時、ここで処理を止めて何もビューに出さない
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: 'json',
            type: 'GET',
            cache: false,
            async: false,
            data: {
                'keyword': keyword,
                'company': company,
                'lower_price': lower_price,
                'upper_price': upper_price,
                'lower_stock': lower_stock,
                'upper_stock': upper_stock
            },
            dataType: 'json', //json形式で受け取る
        }).done(function (data) {
            console.log(data);
            let res = data.products.data;
            console.log(res);
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
                let detail_form = `<form action="http://localhost/sales/public/detail/${id}" method="GET">`;
                detail_form += '<button type="submit" class="btn btn-info">詳細</button>';
                detail_form += '</form>';
                html += `<td class="p-list__detail">${detail_form}</td>`;
                html += `<td class="p-list__remove"><button type="submit" id=${id} class="btn btn-danger">削除</button></td>`;
                $('.p-list tbody').append(html);

            })

            let page = data.products.current_page;
            console.log(page);
            let next_page_url = data.products.next_page_url;
            let prev_page_url = data.products.prev_page_url;
            let last_page = data.products.last_page;
            console.log(next_page_url);
            console.log(prev_page_url);
                console.log(last_page);

            //ページネーター描画
            //Prev 制御
            if (prev_page_url == null) {
                $(".pagination").append('<li class="page-item disabled"><a class="page-link" href="">«</a></li>')
            } else {
                $(".pagination").append("<li class='page-item'><a class='page-link' href='http://localhost/sales/public/list?page="+(page-1)+"'>«</a></li>");
            }

            //ページリンク
            for(var i=0;i<last_page;i++)
            {
                var link_page = i+1;

                //activeにするかどうか
                if(page==link_page)
                {
                    $(".pagination").append("<li class='page-item active'><a class='page-link' href='http://localhost/sales/public/list?page="+link_page+"'>"+link_page+"</a></li>");
                }else{
                    $(".pagination").append("<li class='page-item'><a class='page-link' href='http://localhost/sales/public/list?page="+link_page+"'>"+link_page+"</a></li>");
                }
            }

            //Next制御
            if(next_page_url == null){
                $(".pagination").append("<li class='page-item disabled'><a class='page-link' href=''>»</a></li>");
            }else{
                $(".pagination").append("<li class='page-item'><a class='page-link' href='http://localhost/sales/public/list?page="+(page+1)+"'>»</a></li>");
            }

        }).fail(function () {
            //ajax通信がエラーのときの処理
            console.log('通信に失敗しました。');
        })
    })
})

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

$(function () {
    $(document).on('click', '.p-list__remove button', function () {
        let deleteConfirm = confirm('本当に削除しますか？');

        if (deleteConfirm == true) {
            let clickElement = $(this);
            let userId = clickElement.attr('id');

            $.ajax({
                url: 'delete/' + userId,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
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
