import React from 'react'

export function loadingWait() {
    return (
        <span className="loading wait">Загрузка...</span>
    )
}

export function loadingError(error) {
    return (
        <span className="loading error">
            `Ошибка: ${error.message}`
        </span>        
    )
}

export function itvSiteUrl(path) {
    return frontend.site_url + path.replace(/^\//, '')
}

export function itvAjaxUrl(action) {
    let url = new URL(frontend.ajaxurl)
    url.searchParams.set('action', action)
    return url.toString()
}

export function itvShowAjaxError(errorData) {
    let $ = jQuery;
    if(errorData.message) {
        alert($('<div>').html(errorData.message).text())
    }
    else {
        alert('Ошибка!')
    }

    if(errorData.action) {
        console.log(errorData.action + " failed")
    }

    if(errorData.error) {
        console.log(errorData.error)
    }
}

export function itvShowActionSuccess(actionData) {
    if(actionData.message) {
        alert(actionData.message)
    }

    if(actionData.action) {
        console.log(actionData.action + " success")
    }
}