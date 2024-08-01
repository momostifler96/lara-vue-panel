import BaseChart from "./Chats/BaseChart.vue";
import Section from "./Section.vue";
import StateStyleA from "./States/StateStyleA.vue";
import DataTable from "./Table/ResourceDataTable.vue";

export default <Record<string, any>>{
    state_a: StateStyleA,
    dataTable: DataTable,
    section: Section,
    chart: BaseChart,
}
