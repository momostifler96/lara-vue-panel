export function buildTablePagination(items: any[], current_page: number, per_page: number, search: string | null = null, searchBy: string = 'name') {
    return {
        items: search ? items.filter(item => item[searchBy].includes(search)).slice((current_page - 1) * per_page, current_page * per_page) : items.slice((current_page - 1) * per_page, current_page * per_page),
        pagination: {
            total: items.length,
            per_page: per_page,
            current_page: current_page,
            total_pages: Math.ceil(items.length / per_page),
            from: ((current_page - 1) * per_page) + 1,
            to: current_page * per_page,
        },
    };
};