<template>
    <Card :label="label" :title="title" :has-header="!!(title && title.length > 0)" :class="`col-span-${col_span}`">
        <div class="">
            <apexchart :height="height" :options="options" :type="type" :series="series"></apexchart>
        </div>
    </Card>
</template>
<script setup lang="ts">
import Card from "lvp/Components/Widgets/Card.vue";
const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    col_span: {
        type: Number,
        default: 1,
    },
    categories: {
        type: Array,
        required: true,
    },
    series: {
        type: Array<any>,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    height: {
        type: String,
        default: "100%",
    },
    show_data_labels: {
        type: Boolean,
        default: true,
    },
    type: {
        type: String as () => "bar" | "line" | "area" | "donut" | "pie",
        required: true,
    },
});
// console.log(
//     "props.series.map((it) => it.color)",
//     props.series.map((it) => it.color)
// );
const options = {
    chart: {
        id: "base-chart",
        toolbar: {
            show: false,
        },
    },
    colors: props.series.map((it) => it.color),
    stroke: {
        curve: "smooth",
        colors: undefined,
        width: 4,
    },
    // stacked: false,
    xaxis: {
        labels: {
            show: true,
        },
        categories: props.categories,
    },
    yaxis: {
        labels: {
            show: false,
        },
    },

    // selection: {
    //     xaxis: {
    //         min: new Date("19 Jun 2017").getTime(),
    //         max: new Date("14 Aug 2017").getTime(),
    //     },
    // },
    title: {
        text: props.title,
        align: "left",
        margin: 10,
        offsetX: 0,
        offsetY: 0,
        floating: false,
        style: {
            fontSize: "14px",
            fontWeight: "bold",
            fontFamily: undefined,
            color: "#263238",
        },
    },
    tooltip: {
        enabled: true,
    },
    dataLabels: {
        enabled: props.show_data_labels,
    },
    legend: {
        show: true,
        position: "top",
        horizontalAlign: "center", //center ,left ,right
        markers: {
            size: 6,
            shape: "circle", // circle, square, line, plus, plus
            strokeWidth: 2,
            fillColors: undefined,
            radius: 2,
            customHTML: undefined,
            onClick: undefined,
            offsetX: 0,
            offsetY: 0,
        },
    },
    grid: {
        show: true,
        borderColor: "#EFEFEF",
        strokeDashArray: 0,
        position: "back",
        xaxis: {
            lines: {
                show: false,
            },
        },
        yaxis: {
            lines: {
                show: false,
            },
        },
        row: {
            colors: undefined,
            opacity: 0.5,
        },
        column: {
            colors: undefined,
            opacity: 0.5,
        },
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
        },
    },
    // markers: {
    //     size: 0,
    //     colors: undefined,
    //     strokeColors: "#fff",
    //     strokeWidth: 2,
    //     strokeOpacity: 0.9,
    //     strokeDashArray: 0,
    //     fillOpacity: 1,
    //     discrete: [],
    //     shape: "circle",
    //     radius: 2,
    //     offsetX: 0,
    //     offsetY: 0,
    //     onClick: undefined,
    //     onDblClick: undefined,
    //     showNullDataPoints: true,
    //     hover: {
    //         size: undefined,
    //         sizeOffset: 3,
    //     },
    // },
};

// const series = [
//     {
//         name: "series-1",
//         color: "#aeA",
//         data: [30, 40, 45, 50, 49, 60, 70, 91],
//     },
//     {
//         name: "series-2",
//         color: "#aaA",
//         data: [20, 30, 45, 50, 79, 60, 70, 91],
//     },
// ];
</script>
