import axios from 'axios'

export function get(url, params) {
    return axios({
        method: 'GET',
        url: url,
        params: params
    })
}






export function byMethod(method, url, data) {
    return axios({
        method: method,
        url: url,
        data: data,

        // headers: { 'content-type': 'multipart/form-data' }
        // headers: {
        //       'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
        // }

    })
}
