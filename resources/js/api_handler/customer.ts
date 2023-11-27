import { axiosInstanceCustomer } from "../utils/axios";
import { token } from "@/config";

export type customerProps =  {
    "customer_id" : string;
}

export type customerStatisticProps = {
    "type": string,
    "startdate": string,
    "enddate": string
}

export function customer(params : customerProps){
    axiosInstanceCustomer.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;;
    return axiosInstanceCustomer.post('/api/customer/info', {
        "code": 1,
        "data": {
            "key":params.customer_id
        }
    });
}

export function customerStatistic(params:customerStatisticProps) {
    axiosInstanceCustomer.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;;

    return axiosInstanceCustomer.post('/api/customer/statistics/summary', {
        "data": {
            "type":params.type
        }
    });
}
