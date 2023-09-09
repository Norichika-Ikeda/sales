import './bootstrap';

$(function () {
    $('.p-search__box--btn').on('click', function () {
        $('.p-list tbody').empty(); //もともとある要素を空にする
        $('.search-null').remove(); //検索結果が0のときのテキストを消す

        let keyword = $('#keyword').val(); //検索ワードを取得
        let company = $('#company').val();
        if (!keyword && !company) {
            return false;
        } //ガード節で検索ワードが空の時、ここで処理を止めて何もビューに出さない
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: 'list/' + keyword + company,
            type: 'GET',
            cache: false,
            async: false,
            data: {
                'keyword': keyword,
                'company' : company //ここはサーバーに贈りたい情報。今回は検索ファームのバリューを送りたい。
            },
            dataType: 'json', //json形式で受け取る
        }).done(function (data) {
            console.log(data);
            let res = data.data;
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
                html += `<td class="p-list__detail">${detail_form}</td>`;
                let delete_form = `<form action="http://localhost/sales/public/delete/${id}" method="POST">`;
                delete_form += '<button type="submit" class="btn btn-danger" onclick="return confirm("本当に削除しますか？")">削除</button>';
                html += `<td class="p-list__remove">${delete_form}</td>`;
                html += '</td>';
                $('.p-list tbody').append(html);

            let next_page_url = data.next_page_url;
            let prev_page_url = data.prev_page_url;
            let last_page = data.last_page;
            console.log(next_page_url);
            console.log(prev_page_url);
                console.log(last_page);

                if (prev_page_url == null) {
                    $('.pagination').append('<li class="page-item disabled" aria-disabled="true" aria-label="« Previous"><span class="page-link" aria-hidden="true">‹</span></li>');
                } else {
                    $('.pagination').append('<li class="page-item disabled" aria-disabled="true" aria-label="« Previous"></li>');
            }
            })

        }).fail(function () {
            //ajax通信がエラーのときの処理
            console.log('通信に失敗しました。');
        })
    })
})
