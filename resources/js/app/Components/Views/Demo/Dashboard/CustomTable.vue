<template>
    <table class="table">
        <template v-if="data.length">
            <thead>
            <tr class="datatable-th">
                <th scope="col" :class="`datatable-th pt-0 ${head.alignment === 'right' ? 'text-right' : ''}`" v-for="(head, index) in options.cols"
                    :key="`headers-item-${index}`"><span class="text-capitalize">{{ head.name }}</span></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, row_index) in data" :key="`row-items-${row_index}`">
                <td :class="`datatable-td ${col.alignment === 'right' ? 'text-right' : ''}`" v-for="(col, col_index) in options.cols"
                    :key="`col-item-${row_index}-${col_index}`">
                    <div v-html="row[col.key]" :class="`${col.class} cursor-pointer`" v-if="col.key === 'customer'"></div>
                    <div v-html="row[col.key]" :class="`${col.class} cursor-pointer`" v-else-if="col.type === 'modal'"></div>
                    <div v-html="row[col.key]" :class="col.class" v-else></div>
                </td>
            </tr>
            </tbody>
        </template>
        <app-empty-data-block
            v-else
            :message="$t('empty_data_block_dummy_message')"
        />
    </table>
</template>
<script>
import {urlGenerator} from "../../../../Helpers/AxiosHelper";
export default {
    props: ['options', 'data'],
    data() {
        return {
        }
    },
    methods: {
        handleCustomTableModalClick(invoiceId) {
            this.$emit('activate-invoice-view-modal', invoiceId)
        },
        handleCustomTableLinkClick(url) {
            window.location.replace(urlGenerator(url));
        }
    }
}
</script>









