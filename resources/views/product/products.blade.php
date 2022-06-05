@extends('layouts.master')
@section('header')

@endsection


@section('content')

    <div id="app" class="products">

        <div class="products_header">
            <div class="products_title">
                <h1>{{ ! (count($products) == 0 ) ?  "محصولات  : " . $category->name  : "محصولی برای نمایش وجود ندارد"}}</h1>

            </div>
            <div class="products_details">
                <div class="products_details">
                    <div id="prof_search_btn" class="products_prof_search">
                        <i class="far fa-file-search"></i>
                        <span>جست و جوی پیشرفته</span>
                    </div>
                    <div id="products_sort_btn" class="products_sort">
                        <i class="fad fa-sort-amount-down"></i>
                        <span>مرتب سازی</span>
                    </div>

                </div>

            </div>
        </div>

        <div class="products_list">

            <product-item v-show="check_inventory(product)" v-for="product in sortArrays(paginate) "
                          :product="product"></product-item>
            {{--         <product-item v-else> موردی برای نمایش نیست !!</product-item>--}}

        </div>
        <ul class="vue_paginate_product">
            <li v-for="pageNumber in totalPages"
                v-if="Math.abs(pageNumber - currentPage) < 3 || pageNumber == totalPages || pageNumber == 1">
                <a v-bind:key="pageNumber" href="#" @click="setPage(pageNumber)"
                   :class="{current: currentPage === pageNumber, last: (pageNumber == totalPages && Math.abs(pageNumber - currentPage) > 3), first:(pageNumber == 1 && Math.abs(pageNumber - currentPage) > 3)}">@{{
                    $root.farsi(pageNumber) }}</a>
            </li>
        </ul>


        <div id="sort_by" class="modal">

            <div class="modal-content">
                <div class="header">
                    <span class="close">&times;</span>
                    <span>مرتب سازی بر اساس :</span>
                </div>
                <div class="sort_by_body">
                    <label class="container">پر فروش ترین
                        <input type="radio" checked="checked" name="radio">
                        <span class="checkmark"></span>
                    </label>
                    <label @click="sortBy = 'observed' , descOrAsc = 'desc'" class="container">پر بازدید ترین
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
                    <label @click="sortBy = 'created_at' , descOrAsc = 'desc'" class="container">جدید ترین
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
                    <label @click="sortBy = 'pricee' , descOrAsc = 'asc'" class="container">ارزان ترین
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
                    <label @click="sortBy = 'pricee' , descOrAsc = 'desc'" class="container">گران ترین
                        <input type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
                </div>

            </div>

        </div>

        <div id="prof_search" class="modal">

            <div class="modal-content">
                <div class="header">
                    <span class="close">&times;</span>
                    <span>جست و جوی پیشرفته :  {{ ! (count($products) == 0 ) ? $category->name : ""}}</span>

                </div>
                <div class="prof_search_body">
                    <div class="body_header">
                        <div id="delete_all" class="delete_all">
                            <i class="far fa-file-minus"></i>
                            <span>پاک کردن همه</span>
                        </div>

                        <div class="is_exist">
                            فقط کالاهای موجود
                            <label class="switch">
                                <input id="just_exist" type="checkbox" @change="filter_inventory_func()">
                                <span class="slider round"></span>
                            </label>

                        </div>


                    </div>

                    <div class="prof_search_list">

                        <attribute-list @sendtoel="addToProductFilter($event , index)"
                                        v-for="(attribute , index) in product_attributes"
                                        :attribute="attribute" :index="index"></attribute-list>

                    </div>

                </div>

            </div>

        </div>

        <div id="compare_products_button" class="compare_products_button">

            <div hidden id="compare_products_items" class="compare_products_items">

                <product_compare_list v-for="product in products_for_compare" :product="product"></product_compare_list>

            </div>
            <div id="go_to_compare_page" @click="goToComparePage" class="compare_text">
                <span>مقایسه</span>
                <div>
                    <span id="compare_text_counter">@{{this.$root.farsi(products_for_compare.length)}}</span>
                    <span>کالا</span>
                </div>
            </div>

        </div>

        <flash-message class="myCustomClass"></flash-message>
    </div>
@endsection


@section('script')
    <script>


        Vue.component('product_compare_list', {
            props: ['product'],
            template: `
                        <div class="compare_products_item">
                                <div>
                                    <img :src="product.img" alt="">
                                    <span>@{{ product.name }}</span>
                                </div>
                                <i @click="deleteFromCompareList(product.id)" class="fas fa-trash-alt"></i>

                         </div>
           `,
            methods: {
                deleteFromCompareList(id) {
                    app.products_for_compare = app.products_for_compare.filter(item => item.id != id)
                    app.productsafterfilter.map(function (product) {
                        if (product.id == id) {
                            product.isChecked = false;
                        }
                    })
                    if (app.products_for_compare.length > 0) {
                        $('#compare_products_button').fadeIn();
                    } else {
                        $('#compare_products_button').fadeOut();
                    }

                    $("#compare_products_button").hover(function () {
                        $('#compare_products_items').fadeToggle(500);
                    });
                }
            }
        })

        Vue.component('attributeList', {
            props: ['attribute', 'index'],
            template: `
            <div class="prof_search_list_item">
                            <div  @click="show_panel"  class="accordion" >
                                <i class="fal fa-plus"></i>
                                <div >
                                    <span class="accordion_title">@{{ index }}</span>
                                    <span class="accordion_selected"></span>
                                </div>

                            </div>
                            <div class="panel" v-show="display_panel" style="display: flex">

                                     <label v-for="value in attribute"  class="container">@{{ value }}
                                        <input @change="firemethod(value , index)"  type="checkbox"  >
                                        <span  class="checkmark" ></span>
                                     </label>

                            </div>
                        </div>

            `,
            data() {
                return {
                    counter: [],
                    display_panel: false
                }
            },
            methods: {
                firemethod(value, index) {
                    this.$emit('sendtoel', value);

                },
                show_panel() {
                    this.display_panel = !this.display_panel;
                }

            },


        })

        Vue.component('productItem', {
            props: ['product'],
            template: `
             <div  class="products_item">

                    <img :src="product.image" :alt="product.image">
                    <div>
                        <a target="tab" :href="/product/+product.slug">
                        <span id="product_name">@{{product.name}}</span>
                        <input id="product_id" type="hidden" :value="product.id">
                       </a>
                        <div class="products_item_details">
                          <span  v-if="product.inventory>0">موجود</span>
                          <span style="color: #f82d2d" v-else>ناموجود</span>


                            <div  class="products_star">
                                <i class="fal fa-star"></i>
                                <span class="product_rate">@{{  this.$root.farsi(Math.round((product.avrage)*10)/10) }}</span>
                                <span v-if="product.leng == 0" class="product_rate"></span>
                                <span v-if="product.leng > 0" class="product_rate_counter">(@{{ this.$root.farsi(product.leng) }} نفر)</span>
                            </div>

                        </div>
                    <div class="price_compare">
                          <div v-if="product.pricee != 0" class="products_price">
                            <span class="number">@{{ this.$root.farsi(product.pricee) }}</span>
                            <span class="unit">تومان</span>
                          </div>
                          <div class="product_compare">
                             <label style="cursor: pointer" :style="product.isChecked ? {'opacity' : '100%'} : {'opacity' : '50%'}" class="container"> مقایسه
                                 <input @click="do_change" class="compare_check" :checked="product.isChecked"  type="checkbox">
                                 <span  class="checkmark" ></span>
                             </label>
                          </div>

                    </div>

                    </div>


            </div>
            `,
            methods: {
                avgRate(rates) {
                    var sum = 0;
                    for (var rate in rates) {
                        sum = sum + parseFloat(rate);

                    }
                    return sum;
                },
                do_change(event) {

                    // var numberForCompare = $('.compare_check:checked').length;

                    var data = {
                        id: event.currentTarget.parentNode.parentNode.parentNode.parentNode.querySelector("#product_id").value,
                        name: event.currentTarget.parentNode.parentNode.parentNode.parentNode.querySelector("#product_name").innerHTML,
                        img: event.currentTarget.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector('img').src
                    }

                    if (event.currentTarget.checked === true) {
                        // event.currentTarget.parentNode.style.opacity = '100%';

                        if (app.products_for_compare.length === 3) {
                            event.currentTarget.checked = false;
                            app.flashError('حداکثر 3 محصول ');

                        }

                        if (app.products_for_compare.length < 3) {
                            app.compare(data);
                            // $('#compare_text_counter').text(numberForCompare);
                        }

                    } else {
                        // event.currentTarget.parentNode.style.opacity = '50%';
                        app.compare(data);
                        // $('#compare_text_counter').text(numberForCompare);
                    }

                    if (app.products_for_compare.length > 0) {
                        $('#compare_products_button').fadeIn();
                    } else {
                        $('#compare_products_button').fadeOut();
                    }

                },


            },
            mounted: function () {

                /*/////////////////////////////accordion_selected////////////////////////////////////*/


                var containerr = $('.container');
                var accordion_selected = $('.accordion_selected');
                var prof_search_list = $('.prof_search_list');
                checked_number = 0;
                // list_refresh();
                // delete_all_checkboxes();
                containerr.click(function () {
                    list_refresh();
                });

                function list_refresh() {
                    accordion_selected.each(function () {
                        checked_number = $(this).parents('.prof_search_list_item').find('input:checkbox:checked').length;
                        if (!checked_number == 0) {
                            $(this).text(checked_number + ' مورد انتخاب شده');
                        } else {
                            $(this).text('');
                        }
                    });
                }

                var delete_all = $('#delete_all');
                delete_all.click(function () {
                    delete_all_checkboxes();
                    app.refresh_product_list();
                })

                function delete_all_checkboxes() {
                    prof_search_list.find('input:checkbox:checked').prop('checked', false);
                    list_refresh();

                }
            },

        })


        let app = new Vue({
            el: '#app',
            data: {
                sortBy: '',
                filter_inventory: false,
                descOrAsc: 'asc',
                products: [],
                productsafterfilter: [],
                productsfilter: {},
                product_attributes: {},
                currentPage: 1,
                itemsPerPage: 12,
                resultCount: 0,
                products_for_compare: [],
            },

            created: function () {

                let id = {!! ! (count($products) == 0 ) ? $category->id : -1 !!};

                if (!(id == -1)) {
                    axios.get('/product/list/' + id)

                        .then(response => {
                            this.products = response.data;
                            this.productsafterfilter = response.data;
                            this.products.forEach(this.addAttrToDataList);

                        })

                        .catch(function (error) {
                            console.log(error);
                        })

                }
            },
            methods: {
                goToComparePage() {

                    var products = this.products_for_compare;
                    var data = [];

                    products.forEach(getId);

                    function getId(item, index) {
                        data.push(item['id']);
                    }

                    window.location = '/compare/' + JSON.stringify(data);

                },
                farsi(number) {
                    var arabicNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

                    var dotNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    var chars = dotNumber.toString().split('');
                    for (var i = 0; i < chars.length; i++) {
                        if (/\d/.test(chars[i])) {
                            chars[i] = arabicNumbers[chars[i]];
                        }
                    }
                    return chars.join('');
                },
                compare(itemm) {

                    if (this.products_for_compare.some(item => item.id == itemm.id)) {
                        this.products_for_compare = this.products_for_compare.filter(item => item.id != itemm.id)
                        this.productsafterfilter.map(function (product) {
                            if (product.id == itemm.id) {
                                product.isChecked = false;
                            }
                        })
                    } else {
                        this.productsafterfilter.map(function (product) {
                            if (product.id == itemm.id) {
                                product.isChecked = true;
                            }
                        })
                        this.products_for_compare.push(itemm);
                    }

                },
                setPage: function (pageNumber) {
                    this.currentPage = pageNumber
                },
                filter_inventory_func() {
                    this.filter_inventory = !this.filter_inventory;
                },
                sortArrays(arrays) {
                    return _.orderBy(arrays, this.sortBy, this.descOrAsc);
                },
                addAttrToDataList(item, index) {

                    if (Object.keys(item.attr).length > 0) {

                        Object.entries(item.attr).forEach(entry => {
                            const [key, value] = entry;
                            if (!(key in this.product_attributes)) {
                                if (value.length > 1) {
                                    for (i = 0; i < value.length; i++) {
                                        if (i == 0) {
                                            if (value[i].length < 48) {
                                                this.product_attributes[key] = [value[i]];
                                            }
                                        } else {
                                            if (value[i].length < 48) {
                                                this.product_attributes[key].push(value[i]);
                                            }

                                        }
                                    }
                                } else if (value.length == 1) {
                                    if (value[0].length < 48) {
                                        this.product_attributes[key] = [value[0]];
                                    }
                                }
                            } else {
                                if (value.length > 1) {
                                    for (i = 0; i < (value.length); i++) {
                                        if (!this.product_attributes[key].includes(value[i])) {
                                            if (value[i].length < 48) {
                                                this.product_attributes[key].push(value[i]);
                                            }
                                        }
                                    }

                                } else if (value.length == 1) {
                                    if (!this.product_attributes[key].includes(value[0])) {
                                        if (value[0].length < 48) {
                                            this.product_attributes[key].push(value[0]);
                                        }
                                    }
                                }
                            }
                        });
                    }
                },
                addToProductFilter(value, index) {
                    if (!(index in this.productsfilter)) {
                        this.productsfilter[index] = [value];
                    } else if (index in this.productsfilter) {
                        if (!(this.productsfilter[index].includes(value))) {
                            this.productsfilter[index].push(value);
                        } else if (this.productsfilter[index].includes(value)) {
                            const index_value = this.productsfilter[index].indexOf(value);
                            this.productsfilter[index].splice(index_value, 1);
                            if (this.productsfilter[index].length == 0) {
                                delete this.productsfilter[index];

                            }
                        }
                    }

                    if (Object.keys(this.productsfilter).length > 0) {

                        // console.log(this.products[0].attr)
                        this.productsafterfilter = [];
                        this.products.forEach(this.filter_products);

                    } else if (Object.keys(this.productsfilter).length < 1) {
                        this.productsafterfilter = this.products.slice();
                    }


                },
                filter_products(product, index) {

                    if (Object.keys(product.attr).length > 0) {
                        let status = 0;
                        let numberOfProductsFilter = Object.keys(this.productsfilter).length;

                        Object.entries(product.attr).forEach(entry => {
                            const [key, value] = entry;

                            if (key in this.productsfilter) {
                                if (this.productsfilter[key].some(r => value.includes(r))) {
                                    status++;
                                }
                            }
                        });
                        if (numberOfProductsFilter == status) {
                            if (!(this.productsafterfilter.includes(product))) {
                                this.productsafterfilter.push(product);
                            }
                        }
                    }

                },
                refresh_product_list() {
                    this.productsafterfilter = this.products.slice();
                },
                check_inventory(product) {
                    if (this.filter_inventory) {
                        if (product['inventory'] < 1) {
                            return false;
                        } else if (product['inventory'] > 0) {
                            return true;
                        }
                    } else {
                        return true;
                    }
                },

            },
            computed: {

                monitor_inventory: function () {

                    if ($('#just_exist').is(':checked')) {

                        this.productsafterfilter.forEach(this.check_inventory);
                    }
                    if (!($('#just_exist').is(':checked'))) {

                        this.filter_products()
                    }
                },
                totalPages: function () {
                    return Math.ceil(this.resultCount / this.itemsPerPage)
                },
                paginate: function () {
                    if (!this.productsafterfilter || this.productsafterfilter.length != this.productsafterfilter.length) {
                        return
                    }
                    this.resultCount = this.productsafterfilter.length
                    if (this.totalPages < 0) {
                        this.currentPage = 1;
                    }
                    var index = this.currentPage * this.itemsPerPage - this.itemsPerPage
                    return this.productsafterfilter.slice(index, index + this.itemsPerPage)
                },
            },

        });


        $('#compare_products_button').fadeOut(0);
        $('#compare_products_items').fadeOut(0);
        $("#compare_products_button").hover(function () {
            $('#compare_products_items').fadeToggle(500);
        });


        /*////////////////////////////////sort_by/////////////////////////////////*/
        var sort_by = document.getElementById("sort_by");
        var products_sort_btn = document.getElementById("products_sort_btn");

        products_sort_btn.onclick = function () {
            sort_by.style.display = "block";
        }
        /*////////////////////////////////prof_search/////////////////////////////////*/
        var prof_search = document.getElementById("prof_search");
        var prof_search_btn = document.getElementById("prof_search_btn");

        prof_search_btn.onclick = function () {
            prof_search.style.display = "block";
        }

        window.onclick = function (event) {
            if (event.target == prof_search || event.target == sort_by) {
                prof_search.style.display = "none";
                sort_by.style.display = "none";
            }
        }

        var close_modal = document.getElementsByClassName("close");
        var i = 0;
        for (i = 0; i < close_modal.length; i++) {
            close_modal[i].onclick = function () {
                sort_by.style.display = "none";
                prof_search.style.display = "none";
            }
        }


    </script>

@endsection
