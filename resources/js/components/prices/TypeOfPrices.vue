<template>
    <CategoryComponent />
    <div>

        <table>
            <thead>
                <tr>
                    <th> <button>+</button> <span>Найменування</span> </th>
                    <th>рос</th>
                    <th>ціна</th>
                    <th>од. вим.</th>
                    <th>Категорія</th>
                    <th>ТЗ</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="order in orders" :key="order.id">
                    <td>
                        <input type="text" v-model="order.type">
                    </td>
                    <td>
                        <input type="text" v-model="order.poc">
                    </td>
                    <td>
                        <input type="text" v-model="order.price">
                    </td>
                    <td>
                        <select v-model="order.matchingInSearch">
                            <option value="заміна унітаза">заміна унітаза</option>
                            <option value="ремонт крана">ремонт крана</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="order.category">
                            <option value="Сантехніка">Сантехніка</option>
                            <option value="Електрика">Електрика</option>
                        </select>
                    </td>
                    <td>
                       {{order.count}}
                    </td>
                </tr>
            </tbody>
        </table>

        <button class="btn btn-success">Зберегти</button>

    </div>
</template>
<script>
import CategoryComponent from '../categories/Index.vue'

export default {
    components: {
        CategoryComponent
    },

    name: 'PriceOfOrders',
    mounted() {
    },
    data() {
        return {
            orders: [
                {
                    id: 123,
                    price: 123,
                    count: 2,
                    type: 'Установка унітаза',
                    poc: 'установка унитаза',
                    category: 'Сантехніка',
                    matchingInSearch: 'заміна унітаза'
                }
            ],
        };
    },
};
</script>

<style scoped>
.workflow {
    /* Your CSS styles here */
}

.actions {
    /* Styles for the action section */
}

.timeline {
    /* Styles for the timeline section */
}

.dot {
    /* Styles for the dot in the timeline */
}

.action-item {
    /* Styles for each action item */
}

.top-select-1 {
    margin-bottom: 20px;
}

.select2 {
    max-width: 500px;
}

.middle-selector {
    margin-top: 50px;
    display: flex;
    align-items: flex-end;
}

.checkbox {
    display: flex;
    align-items: baseline;
    margin-left: 20px;
    column-gap: 10px;
}

.custom-list:before {
    content: "••";
    font-size: 24px;
    vertical-align: middle;
    padding-left: 10px;
    margin-right: -10px;
}
</style>