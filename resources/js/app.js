import './bootstrap';
import { createApp } from 'vue';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

import DeviceComponent from './components/DeviceComponent.vue';
import PaginationComponent from './partials/PaginationComponent.vue';

const app = createApp({});

window.notyf = new Notyf({
    duration: 5000,
    position: {
      x: "right",
      y: "top"
    },
    types: [
      {
        type: "default",
        backgroundColor: "#3B7DDD",
        icon: {
          className: 'notyf__icon--success',
          tagName: 'i',
        }
      },
      {
        type: "success",
        backgroundColor: "#28a745",
        icon: {
          className: 'notyf__icon--success',
          tagName: 'i',
        }
      },
      {
        type: "warning",
        backgroundColor: "#ffc107",
        icon: {
          className: 'notyf__icon--error',
          tagName: 'i',
        }
      },
      {
        type: "danger",
        backgroundColor: "#dc3545",
        icon: {
          className: 'notyf__icon--error',
          tagName: 'i',
        }
      }
    ]
});

app.component('pagination', PaginationComponent);
app.component('device-component', DeviceComponent);

app.mount('#app');
