import {mapState} from "vuex";
import {FormMixin} from "../../../core/mixins/form/FormMixin";
import HelperMixin from "./HelperMixin";
import {urlGenerator} from "../../Helpers/AxiosHelper";
import {formatted_date} from "../../Helpers/DateTimeHelper";

export default {
    mixins: [FormMixin, HelperMixin],
    data() {
        return {
            loading: false,
            preloader: false,
            message: '',
            type: 'success',
            errors: {},
            formData: {},
            date_format: formatted_date(),
            urlGenerator
        }
    },
    methods: {
        submitData() {
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};

            this.save(this.formData);
        },
        afterSuccess(response) {
            this.loading = false;
            this.message = response.data.message;
        },
        afterError(response) {
            this.message = '';
            this.loading = false;
            this.errors = response.data.errors || {};
            if (response.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterJsValidationFail() {
            this.loading = false;
        },
        beforeGetEditData() {
            this.preloader = true;
        },
        afterSuccessFromGetEditData({data}) {
            this.formData = data;
            this.preloader = false;
        }
    },
    computed: {
        ...mapState({
            statuses: state => state.additional.statuses
        })
    },
}
