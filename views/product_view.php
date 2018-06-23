<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>大同超市</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="/static/weui.min.css" />
    <script src="/static/axios.js"></script>
    <script src="/static/jquery-3.2.1.min.js"></script>
</head>

<body>

    <div class="container" id="container">
        <div class="page tabbar js_show">
            <div class="page__bd" style="height: 100%;">
                <div class="weui-tab">
                    <div class="weui-tab__panel">

                    </div>
                    <div class="weui-tabbar">
                        <a href="/product" class="weui-tabbar__item weui-bar__item_on">
                            <span style="display: inline-block;position: relative;">
                                <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                                <span class="weui-badge" style="position: absolute;top: -2px;right: -13px;" id="count">??</span>
                            </span>
                            <p class="weui-tabbar__label">商品项</p>
                        </a>
                        <a href="#" onclick="render_bound_in()" class="weui-tabbar__item">
                            <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                            <p class="weui-tabbar__label">入库</p>
                        </a>
                        <a href="#" onclick="render_bound_out()" class="weui-tabbar__item">
                            <span style="display: inline-block;position: relative;">
                                <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                                <span class="weui-badge weui-badge_dot" style="position: absolute;top: 0;right: -6px;"></span>
                            </span>
                            <p class="weui-tabbar__label">出库</p>
                        </a>
                        <a href="#" onclick="render_search()" class="weui-tabbar__item">
                            <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                            <p class="weui-tabbar__label">记录</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--伪spa-->
    <div class="weui-cells">





        <div class="weui-cell weui-cell__hd">
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入新的商品项">
            </div>
        </div>
        <a href="#" onclick="add_product()" class="weui-btn weui-btn_primary">提交新增项</a>
        <div id="list-product"></div>
        <div class="weui-loadmore" style="display:none" id="loadinging">
            <i class="weui-loading"></i>
            <span class="weui-loadmore__tips">正在加载</span>
        </div>
        <div class="weui-loadmore weui-loadmore_line" style="display: none;" id="no_date">
            <span class="weui-loadmore__tips">暂无数据</span>
        </div>
    </div>


    <script>
        /**
         * 公共js
         * 
         */
        t = setTimeout(() => {

        }, 0);
        product_data = null
        product_quantity = 0
        var $jObject = $("#list-product");
        page = 0
        status = true

        axios.get("/product/get_count").then(
            function (response) {
                product_quantity = response.data
                $("#count").text(response.data)
            }
        )

        axios.get("/product/get_all_product_list").then(
            function (response) {
                product_data = response.data
            }
        )

        function scrollFunction() {

            if ($(document).scrollTop() >= $(document).height() - $(window).height() && page)


                show_product(page)



        }
        $(window).on("scroll", function () {
            scrollFunction();
        })

        function render_select() {

            var option_list = $(".weui-select")

            for (var j in product_data) {
                option_list.prepend(`
                 <option value=${j}>${product_data[j]}</option>  
                   `)
            }

        }
        function _render(html, render_div) {
            //route

            render_div = render_div || ".weui-cells"
            clean_div(render_div)
            $html = $(html)
            $(render_div).append(html)


        }


        function clean_div(id) {
            id = id || ".weui-cells"
            page = 0
            clearTimeout(t)
            list = document.querySelector(id)
            //清除子节点
            list.innerHTML = "";
        }
        /**
         * 商品项js
         * 
         */
        function add_product() {
            //增加商品

            product_name = document.querySelector(".weui-input");
            //检查数据
            if (product_name.value == "" || product_name.value == undefined) {
                show_info(product_name, "请输入商品名称")
                return false
            }

            axios.get("/product/add", {
                params: {
                    "name": $(".weui-input").val()
                }
            }).then(function () {
                document.querySelector(".weui-input").value = ""
                location.href = "/product";
            })
        }
        function show_product() {
            if (!status) { return false }
            offset = 10
            status = false
            rows = page * offset
            page = page + 1


            if (rows > product_quantity) {
                document.querySelector('#no_date').style.display = "block"
                return false
            }







            axios.get("/product/product_list", {
                params: {
                    "offset": offset,
                    "rows": rows
                }
            })

                .then(function (response) {
                    document.querySelector("#loadinging").style.display = 'block';
                     


                    t = setTimeout(function () {
                        for (var i in response.data) {
                            $c = $(`
                    <a class='weui-cell weui-cell_access' href='#' onclick='modify_route(${i})'>
                            <div class='weui-cell__bd'>
                                <p>${response.data[i]}</p>
                            </div>
                            <div class='weui-cell__ft'>
                            修改
                            </div>
                        </a>
                        `
                            );

                            $("#list-product").append($c);

                        }
                        document.querySelector("#loadinging").style.display = 'none';
                    }, 2000)

                    status = true
                })
        }







        show_product(0)
        /**
         * 
         * 修改页js html-route-modify-delete
         * 
         */

        function render_modify(id, name, price) {
            clean_div()
            $modify_html = $(`
                        <div class="weui-cell weui-cell__hd">
            <div class="weui-cell__bd">
                <a class="weui-input">旧名字:
                    ${name}
                </a>  </div></div>
                <div class="weui-cell">
                       
                        <div class="weui-cell__bd">
                            <input class="weui-input" id="name" type="text"  placeholder="新的商品名称" >
                        </div>
                    </div>
                    
                    
           
            <div class="weui-cell weui-cell__hd">
            <div class="weui-cell__bd">
                <a class="weui-input">老价钱:
                    ${price}
                </a>
                
               
    </div></div>
       
        
<div class="weui-cell">
        
        <div class="weui-cell__bd">
            <input class="weui-input" id="price" type="text"  placeholder="新的商品价格" >
        </div>
    </div>
    
    

        <a href="#" onclick="modify(${id},'${name}',${price})" class="weui-btn weui-btn_primary">提交</a>
        <a href="#" onclick="delete_product(${id})" class="weui-btn weui-btn_warn">删除该商品</a>
                        `)
            $(".weui-cells").append($modify_html)

        }
        function modify_route(id) {

            axios.get("/product/get_product_info", {
                params: {
                    "id": id
                }
            }).then(

                function (response) {
                    data = response.data;
                    name = data.name;
                    price = data.price;
                    render_modify(id, name, price)
                }
            )
        }


        function modify(id, name, price) {
            if ($("#name").val() == "" || $("#name").val() == undefined)
                var name = name
            else
                var name = $("#name").val()
            if ($("#price").val() == "" || $("#price").val() == undefined)
                var price = price
            else
                var price = $("#price").val()
            axios.get("/product/modify_update", {
                params: {
                    "id": id,
                    "name": name,
                    "price": price
                }
            }).then(function () {
                location.href = "/product";
            })

        }
        function delete_product(id) {
            axios.get("/product/delete", {
                params: {
                    "id": id
                }
            }).then(function () {
                location.href = "/product";
            })
        }
        /**
         * 
         * 入库route
         */
        function confirm_order(params) {


            var quantity = $(".weui-input").val()
            if (quantity == "" || quantity == undefined) {
                show_info($(".weui-input"), "请输入入库数量")
                return false
            }
            var condition = $(".weui-select option:selected").val();
            axios.get("/bound/in", {
                params: {
                    "id": condition,
                    "count": quantity
                }
            }).then(function (response) {
                if (response.data.status == false) {
                    show_info($(".weui-input"), "异常")
                }
                else {
                    name = response.data.name;
                    money = response.data.money;
                    var quantity = response.data.quantity;
                    var myDate = new Date();
                    pay_data = myDate.toLocaleDateString();
                    preview_html =
                        `
<div class="weui-form-preview">
    <div class="weui-form-preview__hd">
        <label class="weui-form-preview__label">付款金额</label>
        <em class="weui-form-preview__value">¥${money}</em>
    </div>
    <div class="weui-form-preview__bd">
        <p>
            <label class="weui-form-preview__label">商品</label>
            <span class="weui-form-preview__value">${name}</span>
        </p>
        <p>
            <label class="weui-form-preview__label">库存</label>
            <span class="weui-form-preview__value">${quantity}</span>
        </p>
        <p>
            <label class="weui-form-preview__label">付款日期</label>
            <span class="weui-form-preview__value">${pay_data}</span>
        </p>
        <p>
            <label class="weui-form-preview__label">商家备注</label>
            <span class="weui-form-preview__value">开业大吉开业大吉开业大吉开业大吉开业大吉开业大吉开业大吉开业大吉开业大</span>
        </p>
    </div>
    <div class="weui-form-preview__ft">
        <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="/product" >返回首页</a>
    </div>
</div>
`
                    _render(preview_html);
                }
            })


        }
        function render_bound_in() {


            _render(bound_in_html)
            render_select()


        }

        /**
         * 出库route
         */
        function confirem_the_library() {
            var quantity = $(".weui-input").val()
            if (quantity == "" || quantity == undefined) {
                show_info($(".weui-input"), "请输入出库数量")
                return false
            }
            var condition = $(".weui-select option:selected").val();
            axios.get("/bound/out", {
                params: {
                    "id": condition,
                    "count": quantity
                }
            })
                .then(function (response) {
                    if (response.data.status == false) {
                        show_info($(".weui-input"), "库存不足")
                    }
                    else {

                        money = response.data.money;
                        render_success = `
                    <div class="weui-msg">
    <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">出库成功</h2>
        <p class="weui-msg__desc">您应收到钱数为${money},贺喜!</p>
    </div>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a onclick="render_bound_out()" href ="#" class="weui-btn weui-btn_primary">继续出库</a>
            <a onclick="render_search()" href="#" class="weui-btn weui-btn_default">查询库存</a>
        </p>
    </div>
    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__links">
                <a href="javascript:void(0);" class="weui-footer__link">底部链接文本</a>
            </p>
            <p class="weui-footer__text">Copyright &copy; 2008-2016 </p>
        </div>
    </div>
</div>
                    `
                        _render(render_success)
                    }

                })
        }
        function render_bound_out() {
            _render(bound_out_html)
            render_select()
        }
        /**
         * 搜索route
         */

        function render_search() {
            _render(search_html)
            axios.get("/search").then(
                function (response) {
                    data = response.data
                    search_list = ""
                    for (var i in data) {
                        search_base_list = ` <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>${i}</p>
            </div>
            <div class="weui-cell__ft">${data[i]}</div>
        </div>`
                        search_list += search_base_list
                    }
                    _render(search_list, "#te")
                }
            )






        }

        var bound_in_html = `
<div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">商品名称</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                       
                    </select>
                </div>
            </div>

            <div class="weui-cell weui-cell__hd">
            <div class="weui-cell__hd">
                    <label for="" class="weui-label">商品数量</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="请输入入库数量">
                </div>
            </div>
            <a href="#" onclick="confirm_order()" class="weui-btn weui-btn_primary">确认订单</a>
`
        var bound_out_html = `


            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd">
                    <label for="" class="weui-label">商品名称</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select2">
                        
                    </select>
                </div>
            </div>

            <div class="weui-cell weui-cell__hd">
            <div class="weui-cell__hd">
                    <label for="" class="weui-label">商品数量</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="请输入出库数量">
                </div>
            </div>
            <a href="#" onclick="confirem_the_library()" class="weui-btn weui-btn_primary">确认出货</a>
`

        var search_html = `



 <div class="weui-cells__title">查询结果</div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>商品名称</p>
            </div>
            <div class="weui-cell__ft">商品数量</div>
        </div>
        <div id="te"></div>
`
    </script>



    <script type="text/javascript">

        $(function () {
            $('.weui-tabbar__item').on('click', function () {
                $(this).addClass('weui-bar__item_on').siblings('.weui-bar__item_on').removeClass('weui-bar__item_on');
            });
        });

        var error_msg = document.createElement("div")
        error_msg.className = "weui-toptips weui-toptips_warn js_tooltips"
        document.querySelector("body").appendChild(error_msg)
        function show_info(c, text) {

            document.querySelector(".js_tooltips").innerText = text
            document.querySelector(".js_tooltips").style.display = 'block';
            c.value = ""
            c.focus()
            setTimeout(function () {
                document.querySelector(".js_tooltips").style.display = 'none';

            }, 1500);
        }
    </script>
</body>

</html>