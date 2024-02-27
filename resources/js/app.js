require('./bootstrap');
require('admin-lte');


import { createApp } from 'vue'

//DataTable
//import 'primevue/resources/themes/tailwind-light/theme.css' //theme
import 'primevue/resources/primevue.min.css'  //core css
import 'primeicons/primeicons.css'  //icons

import PrimeVue from 'primevue/config'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';


import axios from 'axios'
import VueAxios from 'vue-axios'
axios.defaults.baseURL = window.axios_defaults_baseURL;

import Tooltip from 'primevue/tooltip';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import OverlayPanel from 'primevue/overlaypanel';
import InputSwitch from 'primevue/inputswitch';
import MultiSelect from 'primevue/multiselect';
import InputNumber from 'primevue/inputnumber';

import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmationService from 'primevue/confirmationservice';
import Dropdown from 'primevue/dropdown';
import SelectButton from 'primevue/selectbutton';
import SplitButton from 'primevue/splitbutton';

import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import InputMask from 'primevue/inputmask';

///import DataLoader from './components/DataLoader.vue';
////import EventSelected from './components/EventSelected.vue';

/*import moment from 'moment';
Vue.prototype.moment = moment
*/


import routes from './routes.js'

import App from './components/App.vue'



const app = createApp(App)
//app.component('welcome', Welcome)
app.use(VueAxios, axios)
app.use(routes)
app.use(ToastService);
app.use(ConfirmationService);



app.use(
PrimeVue,
{
    ripple: true,
    locale: {
        firstDayOfWeek: 1,
        dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        monthNames:	['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        dateFormat:	'mm/dd/yy'
    }
});

//app.use(PrimeVue, { ripple: true });



app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('InputText', InputText);
app.directive('tooltip', Tooltip);
app.component('Calendar', Calendar);
app.component('Button', Button);
app.component('Toast', Toast);
app.component('OverlayPanel', OverlayPanel);
app.component('InputSwitch', InputSwitch);
app.component('MultiSelect', MultiSelect);
app.component('InputNumber', InputNumber);
app.component('ConfirmDialog', ConfirmDialog);
app.component('Dropdown', Dropdown);
app.component('SelectButton', SelectButton);
app.component('SplitButton', SplitButton);

app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('InputMask', InputMask);

// my components
//app.component('DataLoader', DataLoader);


window.vm = app.mount('#app');


app.config.globalProperties.vue_router_base_path = window.vue_router_base_path;








/*
import FrameApp from './components/FrameApp.vue'
const app2 = createApp(FrameApp)
app2.use(VueAxios, axios)

window.vm2 = app2.mount('#app2');*/





