import { createRouter, createWebHistory } from "vue-router";




import DetailQuestion from './components/order/detailQuestion.vue';
import PriceOfOrder from './components/prices/TypeOfPrices.vue';
import TypeOfOrder from './components/order/TypeOfOrder.vue';
import PriceIndex from './components/prices/Index.vue';
import OrderIndex from './components/order/Index.vue';

import Order from './components/order/order.vue';
import Create from './components/order/Create.vue';

import User from './components/user/user.vue';
import UserIndex from './components/user/Index.vue';



export const routes = [

    {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        name: 'home',
        path: '/',
        component: Create,
    },

    {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        name: 'prices',
        path: '/prices',
        component: PriceIndex,
    },

    {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        name: 'typeOfOrder',
        path: '/typeoforder',
        component: TypeOfOrder,
    },

    {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        name: 'priceOfOrder',
        path: '/priceoforder',
        component: PriceOfOrder,
    },

    {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        name: 'detailQuestion',
        path: '/detailQuestion',
        component: DetailQuestion,
    },


    {
        name: 'order',
        path: '/order',
        component: Order,
        children: [
            {
                // UserProfile will be rendered inside User's <router-view>
                // when /user/:id/profile is matched
                name: 'order/index',
                path: '',
                component: OrderIndex,
            },

            {
                // UserProfile will be rendered inside User's <router-view>
                // when /user/:id/profile is matched
                name: 'order/create',
                path: 'create',
                component: Create,
            },

            // {
            //     // UserPosts will be rendered inside User's <router-view>
            //     // when /user/:id/posts is matched
            //     name: 'order/edit',
            //     path: 'edit/:id',
            //     component: OrderCreateOrEdit,
            // },

        ]
    },








    {
        name: 'user',
        path: '/user',
        component: User,
        children: [
            {
                // UserProfile will be rendered inside User's <router-view>
                // when /user/:id/profile is matched
                name: 'user/index',
                path: 'index',
                component: UserIndex,
            }


        ]
    }






];


export default createRouter({
    history: createWebHistory(window.vue_router_base_path),
    routes
})
