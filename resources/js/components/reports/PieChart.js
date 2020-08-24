import { Pie, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
    extends: Pie,
    mixins: [ reactiveProp ],
    props: [ 'options' ],

    data() {
        return {
            options2: {
                legend: {
                    display: true
                },
                maintainAspectRatio : false,
                responsive : true,
                title: {
                    display: true,
                    text: 'DISTRIBUSION PORCENTUAL POR NIVEL ALCANZADO POR LOS DOCENTES'
                },
                showAllTooltips: true
            }
        }
    },
    mounted () {
        // this.chartData is created in the mixin.
        // If you want to pass options please create a local options object
        // this.renderChart(this.chartData, this.options)
        this.renderChart(this.chartData, this.options2)
    }
}