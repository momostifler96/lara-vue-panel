import HTTP from "./http";

class FolderClass {
    id: number | null;
    name: string | null;
    type: string | null;
    is_open: boolean = false;
    base_route: string = '';
    items: number;
    children: FolderClass[] = [];
    parent: FolderClass | null = null;
    constructor(
        data: any,
        route: string,
        children: [] = [],
    ) {
        this.id = data.id;
        this.name = data.name;
        this.type = data.type;
        this.items = data.items;
        this.children = children;
        this.base_route = route;
    }

    async loadChildren($query: string = '') {
        const response = await HTTP.get(route(this.base_route) +
            `?action=load-folders${this.id
                ? `&dir=${this.id}`
                : ""
            }${$query}`
        );
        this.children = response.data.rest_data.folders.map((item: any) => {
            return new FolderClass(item, this.base_route, []);
        });
    }
    setChildren(children: []) {
        this.children = children;
    }
    setParent(parent: any) {
        this.parent = parent;
    }
}

export { FolderClass }
