import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import mask from '@alpinejs/mask'
import Tooltip from "@ryangjchandler/alpine-tooltip"
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'
import flatpickr from "flatpickr"

Alpine.plugin(collapse)
Alpine.plugin(mask)
Alpine.plugin(Tooltip)
Alpine.data('ToastComponent', ToastComponent)
window.Alpine = Alpine;
Alpine.start();

window.flatpickr = flatpickr

new flatpickr(".datetimerangepicker", {
  dateFormat: "d-m-Y H:i",
  enableTime: true,
  time_24hr: true,
  defaultHour: 0,
  mode: "range",
  locale: {
    rangeSeparator: ' - '
  }
})
