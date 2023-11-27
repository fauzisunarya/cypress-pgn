import { axiosInstanceRetail } from "../utils/axios";
import { token } from "@/config";

export type listProps =  {
    "search" : string;
    "setLimit" : any;
    "setOffset" : any;
    "status" : string;
    "type" : string;
    "startdate": any;
    "enddate": any;
    "page": any;
    "limit": any;
    "sortBy": any;
    "order": any;
}

export type listStatusProps =  {
    "search" : string;
}

export type updateTypeProps =  {
    "id" : string;
    "status" : string;
    "remark" : string;
    "value" : number;
    "initial_value" : number;
    "different_value" : number;
}

export function list(params : listProps){
    var page = params.search == '' ? params.page : 1; 
    var offset = (params.page * params.limit) - params.limit;
    axiosInstanceRetail.defaults.headers.common.Authorization = token;
    return axiosInstanceRetail.post('/api/retail/recording-list?page='+page, {
        "data": {
            "order": {
                "column": params.sortBy?params.sortBy:"create_dtm",
                "dir": params.order?params.order:"desc"
            },
            "start": offset?offset:"0",
            "length": params.setLimit!=''?params.setLimit:params.limit?params.limit : 10,
            "search": params.search?params.search:'',
            "status": params.status?params.status:'',
        }
    });
}

export function statusList(params : listStatusProps){
    axiosInstanceRetail.defaults.headers.common.Authorization = token;
    return axiosInstanceRetail.post('/api/retail/status-list', {
        "data": {
            "search": params.search,
        }
    });
}

export function update(params : updateTypeProps){
    axiosInstanceRetail.defaults.headers.common.Authorization = token;
    return axiosInstanceRetail.post('/api/retail/update-recording-meter', {
        "data": {
            "id": params.id,
            "status": params.status,
            "remark": params.remark,
            "value": params.value,
            "initial_value": params.initial_value,
            "different_value": params.different_value

        }
    });
}