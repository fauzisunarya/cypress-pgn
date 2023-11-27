import { axiosInstanceCustomer } from "../utils/axios";
import { token } from "@/config";

export type listProps =  {
    "search" : string;
    "setLimit" : any;
    "setOffset" : any;
    "status" : string;
    "page": any;
    "limit": any;
    "sortBy": any;
    "order": any;
}

export type listStatusProps =  {
    "search" : string;
}

export type detailProps =  {
    "request_id": string;
}

export type deleteProps =  {
    "nomor_pelanggan": string;
}

export type updateTypeProps =  {
    "request_id" : string;
    "nomor_pelanggan": string;
    "status" : string;
    "remark" : string;
    "name" : string;
    "identity" : string;
    "type" : number;
    "phone_number" : string;
    "district" : string;
    "serial_number" : string;
    "last_payment" : string;
}

export type addTypeProps =  {
    "user_id" : string;
    "nomor_pelanggan": string;
}

export function list(params : listProps){
    var page = params.search == '' ? params.page : 1; 
    var offset = (params.page * params.limit) - params.limit;
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/request-list?page='+page, {
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

export function detail(params : detailProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/request-detail', {
        "data": {
            "request_id": params.request_id 
        }
    })
}

export function dummyApi(params : deleteProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/dummy-api', {
        "data": {
            "nomor_pelanggan": params.nomor_pelanggan 
        }
    })
}

export function update(params : updateTypeProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/update-status', {
        "data": {
            "request_id" : params.request_id,
            "nomor_pelanggan": params.nomor_pelanggan,
            "status" : params.status,
            "remark" : params.remark,
            "name" : params.name,
            "identity" : params.identity,
            "type" : params.type,
            "phone_number" : params.phone_number,
            "district" : params.district,
            "serial_number" : params.serial_number,
            "last_payment" : params.last_payment
        }
    });
}

export function deleteAccount(params : deleteProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/terminate', {
        "data": {
            "nomor_pelanggan": params.nomor_pelanggan,
        }
    });
}

export function deleteRequest(params : detailProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/delete', {
        "data": {
            "id": params.request_id,
        }
    });
}

export function addAccount(params : addTypeProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = 'Bearer '+localStorage.getItem('accessToken');
    return axiosInstanceCustomer.post('/api/account/add-account', {
        "data": {
            "user_id" : params.user_id,
            "nomor_pelanggan": params.nomor_pelanggan,
        }
    });
}