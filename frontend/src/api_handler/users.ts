// https://63aafbf8cf281dba8c1697c5.mockapi.io/aryaapi/persons

import { axiosInstanceUam } from "../utils/axios";

export function load(params: any){
    const json = {"guid":0,"code":0,"info":"OK","data":{"pager":{},"list":[{"user_id":5,"code":"aryadiputrak","name":"Arya Diputrak","email":"aryadiputr.kusumah@neuronworks.co.id","language":"en_US","entity_id":null,"create_dtm":"2022-01-20 15:52:36","active_dtm":"2022-01-20 15:52:36","terminate_dtm":"2022-05-31 03:07:21","user_status_id":2,"auth_type_id":1,"auth_dtm":"2022-05-31 03:06:55","nonce":"5916","token":"d5948b872bfa9cd199365489350c11fc","attempts":0,"user_status_name":"Inactive"},{"user_id":13,"code":"feasplatform1","name":"Feasibility Platform 1","email":"laras.rasdiyani.tif419@polban.ac.id","language":"id","entity_id":null,"create_dtm":"2022-07-26 14:37:42","active_dtm":"2022-07-26 14:37:42","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-11-03 13:40:10","nonce":"7573","token":"7560082273140475fad46e21025c3102","attempts":0,"user_status_name":"Active"},{"user_id":19,"code":"abdillah","name":"Abdillah Naufal Hanif","email":"abdillah@neuronworks.co.id","language":"id","entity_id":null,"create_dtm":"2022-10-31 10:52:09","active_dtm":"2022-10-31 10:52:09","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-21 11:17:46","nonce":"7592","token":"b4b53727bff087c92e7a3f566ab2d077","attempts":0,"user_status_name":"Active"},{"user_id":1,"code":"aryadk","name":"Arya Diputra","email":"aryadk@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-01-03 15:21:27","active_dtm":"2022-01-03 15:21:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-23 10:34:39","nonce":"9526","token":"6796c5cc576c39b0e9fb728c85e2ca7e","attempts":0,"user_status_name":"Active"},{"user_id":11,"code":"chelsea","name":"chelsea","email":"chelsea@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-07-08 08:56:06","active_dtm":"2022-07-08 08:56:06","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-07-08 09:07:18","nonce":"4368","token":"0e4636010d7dc28e57dfdddf446648fe","attempts":0,"user_status_name":"Active"},{"user_id":7,"code":"wildan","name":"Wildan","email":"wildan.najah@neuron.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-03-17 05:38:59","active_dtm":"2022-03-17 05:38:59","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-11-01 17:03:48","nonce":"4168","token":"0e395b84934d44a77286c442b065840b","attempts":0,"user_status_name":"Active"},{"user_id":4,"code":"test_ana","name":"Test Ana","email":"ana@gmail.com","language":"id_ID","entity_id":null,"create_dtm":"2022-01-17 10:38:13","active_dtm":"2022-01-17 10:38:13","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-08-08 09:15:38","nonce":"8405","token":"bfddaaea37d2a76cc40845d9fe70f8f3","attempts":0,"user_status_name":"Active"},{"user_id":21,"code":"feasibility3","name":"Feasibility Platformmmx","email":"feasibility3@gmail.com","language":"id_ID","entity_id":null,"create_dtm":"2022-11-10 08:37:27","active_dtm":"2022-11-10 08:37:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":"4977","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":25,"code":"tvebis","name":"Test TV EBIS","email":"alam+tvebis@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-25 16:11:27","active_dtm":"2022-11-25 16:11:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-11-25 16:11:42","nonce":"1229","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":10,"code":"test_luthfi","name":"luthfi","email":"luthfi@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-06-10 07:36:22","active_dtm":"2022-06-10 07:36:22","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-21 14:31:10","nonce":"3035","token":"0d787f7c6b37e1b540cfae1097755c18","attempts":0,"user_status_name":"Active"},{"user_id":9,"code":"maul","name":"Maul","email":"maulana@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-06-02 04:58:27","active_dtm":"2022-06-02 04:58:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-21 11:28:46","nonce":"4006","token":"2d1f165d7c743d790aa02771975c2db4","attempts":0,"user_status_name":"Active"},{"user_id":15,"code":"feasibility2","name":"Feasibility User 2","email":"permadi@neuronworks.co.id","language":"id","entity_id":null,"create_dtm":"2022-08-19 16:27:27","active_dtm":"2022-08-19 16:27:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-08-24 14:33:00","nonce":"8922","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":18,"code":"luthfi","name":"Luthfi","email":"luthfi.s.015@gmail.com","language":"id_ID","entity_id":null,"create_dtm":"2022-10-17 11:40:24","active_dtm":"2022-10-17 11:40:24","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-10-17 13:45:12","nonce":"3693","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":30,"code":"smebis","name":"Test SM EBIS","email":"alam+smebis@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-25 16:54:30","active_dtm":"2022-11-25 16:54:30","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":"2593","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":22,"code":"aryadiputrakusumah","name":"Arya Diputra","email":"aryadiputrakusumah@neuronworks.co.id","language":"id","entity_id":null,"create_dtm":"2022-11-18 14:04:40","active_dtm":"2022-11-18 14:04:40","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":"6558","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":23,"code":"omebis","name":"Test OM EBIS","email":"alam+omebis@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-23 11:23:30","active_dtm":"2022-11-23 11:23:30","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-12-15 22:47:06","nonce":"1444","token":"6639b69603ddaa742e68aeeeadc55d50","attempts":0,"user_status_name":"Active"},{"user_id":8,"code":"aryadiputra","name":"Arya","email":"aryadiputra.kusumah@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-05-31 03:08:56","active_dtm":"2022-05-31 03:08:56","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-15 15:15:36","nonce":"2310","token":"1910f6b29736d1213a75d6660fa2cde7","attempts":0,"user_status_name":"Active"},{"user_id":20,"code":"740209","name":"Salahudin Ardi","email":"740209@telkom.co.id","language":"id","entity_id":null,"create_dtm":"2022-11-09 18:34:02","active_dtm":"2022-11-09 18:34:02","terminate_dtm":null,"user_status_id":3,"auth_type_id":2,"auth_dtm":"2022-11-10 09:43:02","nonce":"5921","token":"de32c5ac24aabfa1f186d77302f26802","attempts":0,"user_status_name":"Active"},{"user_id":26,"code":"btebis","name":"Test Billing Team EBIS","email":"alam+btebis@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-25 16:25:51","active_dtm":"2022-11-25 16:25:51","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":"2000","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":27,"code":"isfan_fauzi","name":"Isfan Fauzi","email":"isfan.fauzi@telkom.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-25 16:47:27","active_dtm":"2022-11-25 16:47:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-12-05 13:05:22","nonce":"9943","token":"3b6dc64f3f134664137bbb05b33ee263","attempts":0,"user_status_name":"Active"},{"user_id":28,"code":"amebis","name":"Test Account Manager EBIS","email":"alam+amebis@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-25 16:53:13","active_dtm":"2022-11-25 16:53:13","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":"5561","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":17,"code":"960431","name":"Irsyad Harfiansyah","email":"960431@telkom.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-10-03 15:56:52","active_dtm":"2022-10-03 15:56:52","terminate_dtm":null,"user_status_id":3,"auth_type_id":2,"auth_dtm":"2022-12-13 17:40:47","nonce":"7018","token":"9548533e945c3e017144e3d29bbda2a6","attempts":0,"user_status_name":"Active"},{"user_id":24,"code":"irsad_shodiq","name":"Irsad Shodiq","email":"irsad.shodiq@telkom.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-24 10:26:02","active_dtm":"2022-11-24 10:26:02","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-01-03 12:37:32","nonce":"2807","token":"91dbb787e7db4161e422e235ccbee712","attempts":0,"user_status_name":"Active"},{"user_id":6,"code":"alam","name":"Alam Aby bashit","email":"alam@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-03-17 05:13:52","active_dtm":"2022-03-17 05:13:52","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-01 20:44:07","nonce":"7634","token":"f6dc4590c988a26bd908c543dc83b40f","attempts":0,"user_status_name":"Active"},{"user_id":12,"code":"nofry","name":"Nofryandi","email":"nofryandi@neuronworks.co.id","language":"id","entity_id":null,"create_dtm":"2022-07-08 14:51:59","active_dtm":"2022-07-08 14:51:59","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-20 15:32:02","nonce":"6949","token":"39dcce94a7645dc36c1ccc659cd4b86c","attempts":0,"user_status_name":"Active"},{"user_id":16,"code":"fitrian","name":"fitrian wijanarko","email":"trian@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-09-21 14:02:55","active_dtm":"2022-09-21 14:02:55","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-22 16:32:45","nonce":"7694","token":"5470ba28d471399ee8ab0ef40802ba53","attempts":0,"user_status_name":"Active"},{"user_id":3,"code":"arya_dk","name":"Arya Dk","email":"arya55diputra@gmail.com","language":"id_ID","entity_id":null,"create_dtm":"2022-01-07 10:39:57","active_dtm":"2022-01-07 10:39:57","terminate_dtm":"2022-05-31 03:07:24","user_status_id":3,"auth_type_id":1,"auth_dtm":"2022-11-25 17:21:26","nonce":"6918","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":29,"code":"seebis","name":"Test Sales Engineer EBIS","email":"alam+seebis@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-11-25 16:53:48","active_dtm":"2022-11-25 16:53:48","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":"3855","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":36,"code":"m010991","name":"Rima Narasari","email":"m010991@gmail.com","language":"id_ID","entity_id":null,"create_dtm":"2023-01-06 14:52:22","active_dtm":"2023-01-06 14:52:22","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":null,"token":null,"attempts":0,"user_status_name":"Active"},{"user_id":37,"code":"superNcx","name":"Administrator","email":"alam+admin@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-01-06 15:27:23","active_dtm":"2023-01-06 15:27:23","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":null,"nonce":null,"token":null,"attempts":0,"user_status_name":"Active"},{"user_id":14,"code":"feasibility","name":"Feasibility Platform","email":"alam+uamfeasibilityplatform@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2022-08-12 08:09:56","active_dtm":"2022-08-12 08:09:56","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-01 20:45:24","nonce":"9906","token":"9fd7fa16561dff44ca3087d96d57d93a","attempts":0,"user_status_name":"Active"},{"user_id":35,"code":"hiba","name":"HIBATUR RIZAL","email":"ss@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-01-06 14:52:22","active_dtm":"2023-01-06 14:52:22","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-01 20:51:33","nonce":"1687","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":33,"code":"nadia_wh","name":"NADIA WareHouse","email":"nadia_wh@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-01-02 16:47:09","active_dtm":"2023-01-02 16:47:09","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-21 10:46:23","nonce":"6975","token":"bb25117174ade460aa6d26cade4039fc","attempts":0,"user_status_name":"Active"},{"user_id":40,"code":"donny","name":"Donny","email":"donny@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-02-08 17:11:41","active_dtm":"2023-02-08 17:11:41","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-09 11:34:31","nonce":"1cae157eeeb8296df8224614fdafebae","token":null,"attempts":0,"user_status_name":"Active"},{"user_id":38,"code":"farid","name":"Farid Firmansyah","email":"farid@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-01-25 15:08:28","active_dtm":"2023-01-25 15:08:28","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-01-25 15:13:14","nonce":"9883","token":"c50fce7b3c46396c250fbb8c3713edd5","attempts":0,"user_status_name":"Active"},{"user_id":32,"code":"nadia_hd","name":"NADIA HelpDesk","email":"nadia_hd@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-01-02 16:46:24","active_dtm":"2023-01-02 16:46:24","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-22 12:59:20","nonce":"2368","token":"a7b035bc67bca40a8c9b89987fa91793","attempts":0,"user_status_name":"Active"},{"user_id":39,"code":"anda.fauzan","name":"Andaresta Fauzan","email":"anda.fauzan@neuronworks.co.id","language":"id","entity_id":null,"create_dtm":"2023-02-03 09:27:27","active_dtm":"2023-02-03 09:27:27","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-09 13:53:36","nonce":"8465","token":"3ead7135502d8647f9c5fac4e681829d","attempts":0,"user_status_name":"Active"},{"user_id":31,"code":"nadia_sa","name":"NADIA Super Admin","email":"nadia_sa@neuronworks.co.id","language":"id_ID","entity_id":null,"create_dtm":"2023-01-02 16:45:47","active_dtm":"2023-01-02 16:45:47","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-22 16:37:40","nonce":"5126","token":"7edc30af8da6a03cf9ee0edcf3e411e4","attempts":0,"user_status_name":"Active"},{"user_id":41,"code":"docking_pefita","name":"Docking Pefita","email":"docking_pefita@neuronworks.co.id","language":"id","entity_id":null,"create_dtm":"2023-02-15 10:25:29","active_dtm":"2023-02-15 10:25:29","terminate_dtm":null,"user_status_id":3,"auth_type_id":1,"auth_dtm":"2023-02-23 10:30:24","nonce":"2869","token":"76f99363fc03dd6edb855a0c13ca636e","attempts":0,"user_status_name":"Active"}],"countTotal":39}};
    // return axiosInstanceUam.post('/addon/composite/user/list',params);
    return {data : json}
}
