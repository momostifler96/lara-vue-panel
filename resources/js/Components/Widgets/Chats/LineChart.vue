<template>
    <apexchart :options="options" :series="series"></apexchart>
</template>
<script setup lang="ts">
const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: false,
    },
    type: {
        type: String as () => "bar" | "line" | "area" | "donut" | "pie",
        required: true,
    },
});

const options = {
    chart: {
        type: "area",
        id: "vuechart-example",
        toolbar: false,
    },
    stroke: {
        curve: "smooth",
    },
    stacked: false,
    xaxis: {
        categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
    },
    labels: ["Apples", "Oranges", "Berries", "Grapes"],
    annotations: {
        yaxis: [
            {
                y: 0,
                y2: null,
                strokeDashArray: 1,
                borderColor: "#c2c2c2",
                fillColor: "#c2c2c2",
                opacity: 0.3,
                offsetX: 0,
                offsetY: -3,
                width: "100%",
                yAxisIndex: 0,
                label: {
                    borderColor: "#c2c2c2",
                    borderWidth: 1,
                    borderRadius: 2,
                    text: undefined,
                    textAnchor: "end",
                    position: "right",
                    offsetX: 0,
                    offsetY: 0,
                    mouseEnter: undefined,
                    mouseLeave: undefined,
                    click: undefined,
                    style: {
                        background: "#fff",
                        color: "#777",
                        fontSize: "12px",
                        fontWeight: 400,
                        fontFamily: undefined,
                        cssClass: "apexcharts-yaxis-annotation-label",
                        padding: {
                            left: 5,
                            right: 5,
                            top: 0,
                            bottom: 2,
                        },
                    },
                },
            },
        ],
        xaxis: [
            {
                x: 0,
                x2: null,
                strokeDashArray: 1,
                borderColor: "#c2c2c2",
                fillColor: "#c2c2c2",
                opacity: 0.3,
                offsetX: 0,
                offsetY: 0,
                label: {
                    borderColor: "#c2c2c2",
                    borderWidth: 1,
                    borderRadius: 2,
                    text: undefined,
                    textAnchor: "middle",
                    position: "top",
                    orientation: "vertical",
                    offsetX: 0,
                    offsetY: 0,
                    mouseEnter: undefined,
                    mouseLeave: undefined,
                    click: undefined,
                    style: {
                        background: "#fff",
                        color: "#777",
                        fontSize: "12px",
                        fontWeight: 400,
                        fontFamily: undefined,
                        cssClass: "apexcharts-xaxis-annotation-label",
                    },
                },
            },
        ],
        points: [
            {
                x: 0,
                y: null,
                yAxisIndex: 0,
                seriesIndex: 0,
                mouseEnter: undefined,
                mouseLeave: undefined,
                click: undefined,
                marker: {
                    size: 0,
                    fillColor: "#fff",
                    strokeColor: "#333",
                    strokeWidth: 3,
                    shape: "circle",
                    radius: 2,
                    OffsetX: 0,
                    OffsetY: 0,
                    cssClass: "",
                },
                label: {
                    borderColor: "#c2c2c2",
                    borderWidth: 1,
                    borderRadius: 2,
                    text: undefined,
                    textAnchor: "middle",
                    offsetX: 0,
                    offsetY: -15,
                    mouseEnter: undefined,
                    mouseLeave: undefined,
                    click: undefined,
                    style: {
                        background: "#fff",
                        color: "#777",
                        fontSize: "12px",
                        fontWeight: 400,
                        fontFamily: undefined,
                        cssClass: "apexcharts-point-annotation-label",
                        padding: {
                            left: 5,
                            right: 5,
                            top: 0,
                            bottom: 2,
                        },
                    },
                },
                image: {
                    path: undefined,
                    width: 20,
                    height: 20,
                    offsetX: 0,
                    offsetY: 0,
                },
            },
        ],

        texts: [
            {
                x: 0,
                y: 0,
                text: "",
                textAnchor: "start",
                foreColor: undefined,
                fontSize: "13px",
                fontFamily: undefined,
                fontWeight: 400,
                appendTo: ".apexcharts-annotations",
                backgroundColor: "transparent",
                borderColor: "#c2c2c2",
                borderRadius: 0,
                borderWidth: 0,
                paddingLeft: 4,
                paddingRight: 4,
                paddingTop: 2,
                paddingBottom: 2,
            },
        ],

        images: [
            {
                path: "",
                x: 0,
                y: 0,
                width: 20,
                height: 20,
                appendTo: ".apexcharts-annotations",
            },
        ],
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
                show: true,
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
    markers: {
        size: 0,
        colors: undefined,
        strokeColors: "#fff",
        strokeWidth: 2,
        strokeOpacity: 0.9,
        strokeDashArray: 0,
        fillOpacity: 1,
        discrete: [],
        shape: "circle",
        radius: 2,
        offsetX: 0,
        offsetY: 0,
        onClick: undefined,
        onDblClick: undefined,
        showNullDataPoints: true,
        hover: {
            size: undefined,
            sizeOffset: 3,
        },
    },
};

const series = [
    {
        name: "series-1",
        color: "#aeA",
        data: [30, 40, 45, 50, 49, 60, 70, 91],
    },
    {
        name: "series-2",
        color: "#aaA",
        data: [20, 30, 45, 50, 79, 60, 70, 91],
    },
];
</script>
