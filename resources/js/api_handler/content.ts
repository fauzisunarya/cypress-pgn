import { axiosContent } from "@/utils/axios";
import { token } from "@/config";

export type listProps =  {
    search? : string;
    setLimit? : any;
    setOffset? : any;
    status? : string;
    module? : string;
    page?: any;
    limit?: any;
    sortBy?: any;
    order?: any;
}

export function list(params: listProps){
    var page = params.search == '' ? params.page : 1; 
    var offset = (params.page * params.limit) - params.limit;
    axiosContent.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;
    return axiosContent.post('/api/contents/list', {
        "data": {
            "order": {
                "column": params.sortBy?params.sortBy:"id",
                "dir": params.order?params.order:"desc"
            },
            "page": page,
            "start": offset?offset:"0",
            "length": params.setLimit!=''?params.setLimit:params.limit?params.limit : 10,
            "search": params.search?params.search:'',
            "status": params.status?params.status:'',
            "module": params.module?params.module:'',
        }
    });
}

export function deleteContent(uuid: any){
    axiosContent.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;
    return axiosContent.post('/api/contents/delete-content', {
        "data": {
            "id": uuid,
        }
    });
}

export function getContent(uuid: any){
    axiosContent.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;
    return axiosContent.get('/api/contents?id='+uuid);
}

export type createProps =  {
    "uuid": string;
    "name" : string;
    "lang" : string;
    "category_id" : string;
    "start_date" : Date;
    "end_date" : string;
    "content_body" : any;
    "created_date": string;
    "last_update": string;
    "type_create": string;
}

export function createContent(params: createProps){
    axiosContent.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;
    return axiosContent.post('/api/contents/'+params.type_create, {
        "data": {
            "content_id": params.uuid,
            "name": params.name,
            "category_id":params.category_id,
            "start_date": params.start_date,
            "end_date": params.end_date,
            "format": "json",
            "module": "news",
            "language": params.lang,
            "status": 1,
            "content_body" : params.content_body,
            "created_date" : params.created_date,
            'last_update' : params.last_update
        }
    });
}