// https://63aafbf8cf281dba8c1697c5.mockapi.io/aryaapi/persons

import axiosContent from "src/utils/axios";
import { token } from "src/config";

export type listProps =  {
    "search" : string;
    "setLimit" : any;
    "setOffset" : any;
    "status" : string;
    "module" : string;
    "startdate": any;
    "enddate": any;
    "page": any;
    "limit": any;
    "sortBy": any;
    "order": any;
}

export function list(params: listProps){
    var page = params.search == '' ? params.page : 1; 
    var offset = (params.page * params.limit) - params.limit;
    axiosContent.defaults.headers.common.Authorization = token;
    return axiosContent.post('/apidoor/contents/list', {
        "data": {
            "order": {
                "column": params.sortBy?params.sortBy:"nid",
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
    axiosContent.defaults.headers.common.Authorization = token;
    return axiosContent.post('/apidoor/contents/delete-content', {
        "data": {
            "uuid": uuid,
            "module": "news",
        }
    });
}

export function getContent(uuid: any){
    axiosContent.defaults.headers.common.Authorization = token;
    return axiosContent.get('/apidoor/contents/'+uuid);
}

export type createProps =  {
    "uuid": string;
    "name" : string;
    "lang" : string;
    "content_body" : any;
    "content_image": any;
    "created_date": Date;
    "last_update": Date;
    "type_create": string;
}

export function createContent(params: createProps){
    axiosContent.defaults.headers.common.Authorization = token;
    return axiosContent.post('/apidoor/contents/'+params.type_create, {
        "data": {
            "uuid": params.uuid,
            "name": params.name,
            "module": "news",
            "lang": params.lang,
            "status": 1,
            "content_body" : params.content_body,
            "content_image" : params.content_image,
            "created_date" : params.created_date,
            'last_update' : params.last_update
        }
    });
}