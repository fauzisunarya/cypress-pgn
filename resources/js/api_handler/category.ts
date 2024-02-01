import { axiosContent } from "@/utils/axios";
import { token } from "@/config";

export function list(params){
    var page = params.page == '' ? params.page : 1; 
    var offset = (params.page * params.limit) - params.limit;
    axiosContent.defaults.headers.common.Authorization = localStorage.getItem('accessToken') ? 'bearer '+localStorage.getItem('accessToken') : token;
    return axiosContent.post('/api/contents/category-list', {
        "data": {
            "order": {
                "column": 'id',
                "dir": 'asc'
            },
            "search": params?params:'',
        }
    });
}