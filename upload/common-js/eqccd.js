/**
 * Created by zhangchao8189888 on 15-6-12.
 */
/// <reference path="Simple.js" />
var wForm;
var iptCustID;
var iptCustName;
function sortField(queryString) {
    var url = qc.applicationPath() + "/Common/BaseData/FieldSort.aspx" + queryString;
    var frame = "<iframe src=\"" + url + "\" frameborder=\"0\" scrolling=\"no\" height=\"" + (550 - 40) + "px\" width=\"" + (400 - 12) + "px\"></iframe>";
    wForm = new UI.openForm(400, 550, Language.Sort, frame, null, true);
}
/********查询区折叠功能******/
var searchBox = {
    hiddenTr: null,
    hiddenShow: null,
    init: function (hidCurrent, hiddeDivID) {
        searchBox.hiddenTr = $(".hiddentr");
        if (searchBox.hiddenTr.length == 0)
            searchBox.hiddenTr = $("#" + hiddeDivID);
        searchBox.hiddenShow = $("#" + hidCurrent);
        var moreIcon = $("#moreIcon");
        moreIcon.bindEvent("click", function () {
            var o = this;
            if (o.className == "exp2") {
                o.className = "exp1";
                o.innerHTML = Language.Folding;
                searchBox.hiddenTr.show();
                searchBox.hiddenShow.val("1");
            }
            else {
                o.className = "exp2"
                o.innerHTML = Language.More;
                searchBox.hiddenTr.hide();
                searchBox.hiddenShow.val("0");
            }
        });
        if (searchBox.hiddenShow.html() != undefined && searchBox.hiddenShow.val() == "1") {
            searchBox.hiddenTr.show();
            moreIcon.css("exp1");
            moreIcon.html(Language.Folding);
        }
    }
}
var qc = {
    /*获取虚拟路径*/
    applicationPath: function () {
        var sPathName = document.location.pathname;
        var iCount = sPathName.indexOf("/", 1);
        var sPath = sPathName.substring(0, iCount);
        return sPath;
    },
    /*Ajax提交数据,async为false则为同步**/
    post: function (dll, className, pars, callback, async) {
        pars["dll"] = dll;
        pars["class"] = className;
        var url = qc.applicationPath() + "/common/mod/ajax.ashx";
        Ajax.post(url, callback, pars, null, async);
    },
    getSelected: function (gridID) {
        var list = $("#" + gridID + " input");
        var ids = "";
        var j = 0;
        for (var i = 0; i < list.length; i++) {
            var o = list[i];
            if (o.type != "checkbox") continue;
            if (o.checked == false) continue;
            var m = parseInt(o.value);
            if (m.toString() == "NaN") continue;
            if (j == 0)
                ids = m;
            else
                ids = ids + "," + m;
            j++;
        }
        return ids;
    },
    syncPost: function (ddl, className, pars, callback, errorHandler) {
        pars["dll"] = ddl;
        pars["class"] = className;
        jQuery.ajax({
            async: false,
            type: "post",
            url: qc.applicationPath() + "/common/mod/ajax.ashx",
            data: pars,
            success: callback,
            error: errorHandler
        });
    },
    asyncPost: function (ddl, className, pars, callback, errorHandler) {
        pars["dll"] = ddl;
        pars["class"] = className;
        jQuery.ajax({
            type: "post",
            url: qc.applicationPath() + "/common/mod/ajax.ashx",
            data: pars,
            success: callback,
            error: errorHandler
        });
    },
    /* 打开模式窗口 */
    showDialog: function (title, url, width, height) {
        var appPath = this.applicationPath();
        return window.showModalDialog(appPath + '/MidFrame.aspx?Title=' + escape(title)
            + '&PathURL=' + appPath + url, "Dialog", "dialogHeight:" + height
            + "px; dialogWidth: " + width + "px; center: yes;help: no;status:no");
    },
    /*打开新窗口*/
    open: function (url, width, height, showscroll) {
        var s = "yes";
        if (showscroll == false)
            s = "no";
        var l = Math.ceil((window.screen.width - width) / 2);
        var t = Math.ceil((window.screen.height - height) / 2); //确定网页的坐标
        return window.open(url, "_blank", "left=" + l + ",top=" + t + ",height=" + height + ",width=" + width + ",toolbar=no,status=no,resizable=yes,location=no,scrollbars=" + s);
    },
    /*打开url,自动居中,url的传入格式:/common/test.aspx,2011.4.9全部统一使用该方法*/
    openWindow: function (url, width, height, showscroll, addUrl) {
        if (!width) width = 900;
        if (!height) height = 600;
        if (!showscroll) showscroll = true;
        //由于某些页面跳转已经有完整的路径，故加此参数做跳板
        if (addUrl) {

        } else {
            url = qc.applicationPath() + url;
        }
        return qc.open(url, width, height, showscroll);
    },
    // 显示窗口
    openModalDialog: function (url, title, h, w) {
        if (!h) h = 600;
        if (!w) w = 900;
        if (!title) title = "";
        url = url.replace(/&/g, "$");
        return window.showModalDialog(qc.applicationPath() + '/MidFrame.aspx?Title=' + escape(title) + '&PathURL=' + url, "IsModalDialog", "dialogHeight:" + h + "px; dialogWidth: " + w + "px; center: yes;help: no;status:no;");
    },
    /*打开新新窗口，统一使用openWindow后，然后删除改方法*/
    wShow: function (url, width, height, showscroll) {
        url = qc.applicationPath() + url;
        qc.open(url, width, height, showscroll);
    },
    /* 去两边空格 */
    trim: function (val) {
        val = this.trimLeft(val);
        val = this.trimRight(val);
        return val;
    },
    /* 去左边空格 */
    trimLeft: function (val) {
        while (val.indexOf(" ") == 0) {
            val = val.replace(" ", "");
        }
        return val;
    },
    /* 去右边空格 */
    trimRight: function (val) {
        while (val.indexOf(" ") == val.length - 1) {
            val = val.substring(0, val.length - 1);
        }
        return val;
    },
    /* 替换字符串 */
    replaceAll: function (sourceStr, findStr, replaceStr) {
        while (sourceStr.indexOf(findStr) >= 0) {
            sourceStr = sourceStr.replace(findStr, replaceStr);
        }
        return sourceStr;
    },
    /*onkeypress检测小数*/
    checkDecimal: function (e, o) {
        var a = $.event.key(e);

        if (a == 13) return false;
        if (o.value.indexOf('.') > -1 && a == 46) return false;
        else {
            if (a == 46) return true;
            if (a >= 48 && a <= 57) return true;
            return false;
        }
    },
    /*onkeypress检测数字*/
    checkNumber: function (e) {
        var a = $.event.key(e);
        if (a == 13) return false;
        if (a >= 48 && a <= 57) return true;
        return false;
    },
    /* 验证 */
    checkText: function (regex, text) {
        return regex.test(text);
    },
    /* 获取选中ID */
    getSelectedId: function (o) {
        if (o) {
            var IDs = "";
            var inputs = o.getElementsByTagName("input");
            for (var i = 0; i < inputs.length; i++) {
                var input = inputs[i];
                if (input.type != "checkbox") continue;
                if (!input.checked) continue;
                if (isNaN(input.value)) continue;
                IDs += input.value + ",";
            }
            return IDs.substring(0, IDs.length - 1);
        }
        else {
            throw new Error("argument can not null!");
        }
    }, /*不经允许不得修改此函数*/
    setAutoHeight: function (obj, iframe) {
        var paddingVal = 0;
        if (!obj.style.paddingTop && window.getComputedStyle) {
            var objStyle = window.getComputedStyle(obj, '');
            paddingVal = parseInt(objStyle.paddingTop, 10) + parseInt(objStyle.paddingBottom, 10);
        }
        // 不能使用onpropertychange事件 造成死循环
        obj.style.height = (obj.scrollHeight - paddingVal) + "px";
        var p1 = window.parent.document.getElementById("frame11");
        var p2 = window.parent.parent.document.getElementById("frame1");
        if (window.parent && iframe) {
            if (window.parent.document.getElementById(iframe)) {
                window.parent.document.getElementById(iframe).style.height = window.parent.document.getElementById(iframe).contentWindow.document.documentElement.scrollHeight + "px";
            }
            if (p1) p1.style.height = p1.contentWindow.document.documentElement.scrollHeight + "px";
            if (p2) p2.style.height = p2.contentWindow.document.documentElement.scrollHeight + "px";
        }
    },
    /* 打开客户中心 */
    openCustCenter: function (id) {
        var url = "/Common/Client/ClientCenter.aspx?operation=edit&popup=1&ID=" + id;
        qc.openWindow(url, 1020, 650, true);
    },
    /* 联系客户 */
    contactCust: function (id) {
        var url = "/Common/Client/ContactHistoryEdit.aspx?operation=add&typeMode=1&custid=" + id;
        qc.openWindow(url, 820, 600, true);
    },
    /* 打开客户选择页面 */
    selectCust: function (txtID, txtName, single, from) {
        var url = "/Common/Client/SelectClient.aspx";
        url += "?custidControl=" + txtID;
        url += "&custnameControl=" + txtName;
        url += "&a=1";
        if (typeof (single) != undefined && single != null) {
            url += "&more=" + (single == 1 || single == "1" ? 0 : 1);
        }
        if (typeof (from) != undefined && from != null) {
            url += "&from=" + from;
        }
        qc.openWindow(url, 800, 600, true);
    }, /* 获取新编号*/
    getNewNo: function (kind, txtID) {
        qc.post("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.BaseSet", { Key: "GetOrderNO", kind: kind }, function (data) {
            if (document.getElementById(txtID)) {
                document.getElementById(txtID).value = data;
            }
        });
    },
    getOrderNo: function (businessType, txtid) {
        if (businessType == null || businessType.length == 0) {
            alert("未指定单号前缀。");
        } else {
            qc.post("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.OrderBase", { Key: "GetOrderNO", "BusinessType": businessType }, function (data) {
                result = data;
                var txt = document.getElementById(txtid);
                if (txt) {
                    txt.value = result;
                }
            });
        }
    }, /* 检查单据编号是否已存在*/
    isExistBillNo: function (BillNo, Type, retValue) {
        qc.post("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.OrderBase", { Key: "IsExistBillNo", "BillNo": BillNo, "Type": Type }, function (data) {
            document.getElementById(retValue).value = data;
        }, false);
    },
    selectUser: function (tabs, index, selected, hr, single, step, level, c) {
        hr = hr == null ? 0 : hr;
        single = single == null ? 0 : single;
        step = step == null ? 0 : step;
        level = level == null ? 0 : level;
        qc.openWindow("/common/mod/SelectUser.aspx?tabs=" + tabs + "&selected=" + selected + "&single=" + single + "&index=" + index + "&hr=" + hr + "&step=" + step + "&level=" + level + "&c=" + c, 640, 600, false);
    },
    selectUsers: function (selected, categoryId, single) {
        if (!single) single = 0;
        qc.openWindow("/common/mod/SelectUsers.aspx?selected=" + selected + "&categoryId=" + categoryId + "&single=" + single, 650, 600, false);
    },
    convertToNewType: function (type) {
        var thisType;
        switch (type) {
            case '0': thisType = '3'; break;
            case '1': thisType = '4'; break;
            case '2': thisType = '1'; break;
            case '3': thisType = '0'; break;
            case '4': thisType = '2'; break;
        }
        return thisType;
    }, convertToOldType: function (typeStr) {
        var type;
        switch (typeStr) {
            case '0': type = '3'; break;
            case '1': type = '2'; break;
            case '2': type = '4'; break;
            case '3': type = '0'; break;
            case '4': type = '1'; break;
        }
        return type;
    }, insertItemFirst: function (oSelect, text, value) {
        if (oSelect != null) {
            oSelect.options.add(new Option(text, value), 0);
        }
    }, setDataGridColumn: function (model, datagrid) {
        var url = "/Common/BaseData/DataGridColumnSetup.aspx?model=" + model;
        if (datagrid) {
            url += "&datagrid=" + datagrid;
        }
        qc.openWindow(url, 640, 560, true);
    },
    getKeyCode: function (e) {
        if (!e) e = event;
        if (navigator.appName == "Microsoft Internet Explorer")
            return e.keyCode;
        else
            return e.which;
    },
    getBirthday: function (age, birthdayid) {
        var ageVal = age.value;
        if (!/^\d+$/.test(ageVal))
            age.value = "";
        else
        if (document.getElementById(birthdayid).value.length < 1)
            document.getElementById(birthdayid).value = (new Date().getFullYear() - ageVal) + "-01-01";
        else
            document.getElementById(birthdayid).value = (new Date().getFullYear() - ageVal) + document.getElementById(birthdayid).value.substr(4, 6);
    },
    getIdentityCard: function (id) {
        id = id.replace(/\s/g, "");
        var len = id.lenght;
        if (len != 15 && len != 18) return null;
        try {
            var lenEQ15 = (len == 15)
            var subBase = lenEQ15 ? 0 : 2;
            var yearBase = lenEQ15 ? 1900 : 0;
            var year = parseInt(id.substr(6, 2 + subBase)) + yearBase;
            var month = parseInt(id.substr(8 + subBase, 2));
            var day = parseInt(id.substr(10 + subBase, 2));
            var gender = (parseInt(id.substr(14 + (subBase * 2), 1)) % 2 == 0) ? 2 : 1;
            return { birthday: new Date(year, month, day), age: new Date().getFullYear - year, sex: gender };
        } catch (e) { return null; }
    },
    createCust: function (o, custid, custname, title, onlyenterprise/*仅企业*/, notchkcontact/*不验证联系必填*/) {
        if (onlyenterprise == undefined) onlyenterprise = 0;
        if (notchkcontact == undefined) notchkcontact = 0;
        var url = [
            "url:", qc.applicationPath(), "/Common/Client/ClientRapidNew.aspx",
            "?id=", custid,
            "&name=", custname,
            "&onlyenterprise=", onlyenterprise,
            "&notchkcontact=", notchkcontact
        ].join("");
        wForm = new UI.openForm(440, 440, title, url, null, false);
        var box = $("#tmpopen");
        var pos = $(o).position();
        box.position(pos.top - 20, pos.left - 320);
    },
    setFocus: function (obj) {
        try { obj.focus(); } catch (e) { }
    },
    displaySet: function (type, code, width, height) {
        this.openWindow("/Common/Mod/DataFieldSetting.aspx?Code=" + code + "&Type=" + type, width, height, true);
    }
}
// 显示收款单
function ShowReceBill(id, para) {
    url = "/Common/Financial/ReceiptBillOpt.aspx?operation=edit&id=" + id;
    if (para != null) {
        if (para.substring(0, 1) != "&") url = url + "&";
        url = url + para;
    }
    qc.openWindow(url, 900, 600, false, false);
}

// 显示付款单
function ShowPayBill(id, para) {
    url = "/Common/Financial/PaymentBillOpt.aspx?operation=edit&id=" + id;
    if (para != null) {
        if (para.substring(0, 1) != "&") url = url + "&";
        url = url + para;
    }
    qc.openWindow(url, 900, 600, false, false);
}
function RecePayDetailBalance(typeid, id) {
    var url;
    if (typeid == 1) {
        url = "/Common/Financial/ReceiptBillOpt.aspx?operation=add&settleopt=1";
    }
    else if (typeid == 2) {
        url = "/Common/Financial/PaymentBillOpt.aspx?operation=add&settleopt=1";
    }
    url = url + "&RecePayDetailID=" + id + "&popup=1";
    qc.openWindow(url, 900, 600, true);
}
function ShowBusinessObject(typeid, id) {
    var url = "";
    switch (typeid.toString()) {
        case "-1": if (id != "0") url = "/Common/Financial/AccountTransferOpt.aspx"; break;  //单位账户转账
        case "11": url = "/CRM/Sales/SalesOrderEdit.aspx"; break; //销售订单
        case "21":
        case "22": url = "/Common/Financial/Invoices/InvoiceOpt.aspx"; break; // 发票
        case "51": url = "/Common/Contract/ContractOpt.aspx"; break; //合同信息
        case "41": url = "/Common/Financial/ReceiptBillOpt.aspx"; break; //收款单
        case "42": url = "/Common/Financial/PaymentBillOpt.aspx"; break; //付款单
        case "43": url = "/CRM/RecePay/InstalmentEdit.aspx"; break; //分期款项
        case "45": url = "/CRM/RecePay/BadDebtBillEdit.aspx"; break; //坏账单
        case "62": url = "/DispartSell/Purchase/RecordPurchase.aspx"; break; //采购订单
        case "63": url = "/DispartSell/Purchase/PurBackEdit.aspx"; break; //采购退货单
        case "64":                                                   //采购入库单
        case "70":                                                   //入库单
        case "67": url = "/DispartSell/Store/InStoreAdd.aspx"; break;//销售退货入库单
        case "65":                                                   //采购退货出库单
        case "69":                                                   //出库单
        case "66": url = "/DispartSell/Store/OutStoreAdd.aspx"; break;//销售出库单
        case "47": url = "/DispartSell/SaleManage/OrderOpt.aspx"; break; //销售订单
        case "48": url = "/DispartSell/SaleManage/WithdrawOpt.aspx"; break; //销售退货单
        case "68":
        case "49": url = "/DispartSell/SaleManage/MoneySell.aspx"; break; //现款销售单
        case "124": url = "/HMS/Projects/ProjectAdd.aspx"; break; //猎头项目
        case "125": url = "/CRM/ClaimsAndDebts/ClaimsOpt.aspx"; break; //债权债务借款合同
        case "126": url = "/CRM/ClaimsAndDebts/DebtsOpt.aspx"; break; //债权债务系统 理财合同单据
    }
    if (url.length == 0) return;
    if ("-1,47,48,49,62,63,125,126".indexOf(typeid) != -1)
        url = url + "?popup=1&operation=view&id=" + id;
    else if (typeid == "21")
        url = url + "?popup=1&operation=edit&ID=" + id + "&type=1";
    else if (typeid == "22")
        url = url + "?popup=1&operation=edit&ID=" + id + "&type=2";
    else if (typeid == "124")
        url = url + "?popup=1&operation=view&type=2&ProjectID=" + id;
    else
        url = url + "?popup=1&operation=edit&id=" + id;
    qc.openWindow(url, 900, 600);
}
var qsver = {
    loading: function (type, left, top) {
        var strHtml;
        switch (type) {
            case 0:
                strHtml = "<div style='padding:5px 20px;border:1px solid #6a9fe2;width:150px;font-size:13px;-moz-border-radius:10px;-webkit-border-radius:10px;text-align:left;position:relative;left:100px;top:5px;background-color:#bed3ef;color:#FFFFFF;'>" +
                    "<img src='" + qc.applicationPath() + "/images/loadinfo.gif'  border='0' />&nbsp;<img src='" + qc.applicationPath() + "/images/loading.gif' border='0' /></div>";
                break;
        }
        return strHtml;
    }
}
/*****************************From ioa.js********************************/
// 获取新编号
function GetNewCode(kind, txtID) {
    qc.getNewNo(kind, txtID);
}
// 获取单号
function GetOrderNo(businessType, txtid) {
    qc.getOrderNo(businessType, txtid);
}
/*******ioa.js中选择人员公用方法，先移动至此，修改方法名**********/
/**************选择客户*********************/
function ChooseCust(txtID, txtName, custName) {
    objID = document.getElementById(txtID);
    objName = document.getElementById(txtName);
    s_txtID = txtID;
    s_txtName = txtName;
    para = "";
    if (custName != null) para = "&custname=" + custName;
    url = "/Common/Client/ClientList.aspx?mode=choosesingle&custidControl=" + txtID + "&custnameControl=" + txtName + para;
    qc.openWindow(url, 950, 600, true);
}
// 选择应收应付账户
function ChooseRecePay(txtID, txtName, custName) {
    s_txtID = txtID;
    s_txtName = txtName;
    var objID = document.getElementById(txtID);
    var objName = document.getElementById(txtName);
    var para = "";
    if (custName != null) para = "&custname=" + custName;
    url = "/Common/Client/ClientList.aspx?mode=choosesingle&onlyRecepay=1" + para;
    qc.openWindow(url, 900, 600, true);
}
function setChoose(r) {

    objID = document.getElementById(s_txtID);
    objName = document.getElementById(s_txtName);
    if (r != null && objID != null && objName != null) {
        if (r[3] == "OnlyRecepay") {
            objID.value = r[0];
            objName.value = unescape(r[2]);
            objName.title = objName.value;
        } else {
            objID.value = r[0];
            objName.value = unescape(r[2]);
            try {
                LoadLinkmanList(r);
            } catch (e) { }
        }
    }

    //用户联系记录详细信息OM/Common/Client/ContactHistoryEdit.aspx
    try {
        document.getElementById("_ctl0_WorkButton_lknRefresh1").click();
    } catch (e) { }

}
/***************选择联系人********************/
function ChooseSingleLinkman(txtCustID, txtID, txtName) {
    objID = document.getElementById(txtID);
    objName = document.getElementById(txtName);
    s_txtID = txtID;
    s_txtName = txtName;
    custid = document.getElementById(txtCustID).value;

    var reNum = /^\d*$/;
    if (!reNum.test(custid)) {
        alert('请先选择客户！')
        return;
    }
    if (parseInt(custid) <= 0) {
        alert('请先选择客户！')
        return;
    }
    //if (custid == "" || custid =="0") {
    //    alert('请先选择客户！')
    //    return;
    //}
    url = "/Common/Client/LinkmanList.aspx?mode=ChooseSingle&custid=" + custid + "&selected=" + objID.value;
    qc.openWindow(url, 800, 600, true);
    //result = window.showModalDialog('../../MidFrame.aspx?Title=' + escape('选择联系人') + '&PathURL=' + url, null, "dialogHeight:400px; dialogWidth: 750px; center: yes;help: no;status:no;");            return result;
}
function setChooseLinkman(result) {
    objID = document.getElementById(s_txtID);
    objName = document.getElementById(s_txtName);
    if (result != null && objID != null && objName != null) {
        objID.value = result[0];
        objName.value = result[1];
        try {
            // 获取联系人的联系电话
            LoadLinkmanTel(objID.value);
        } catch (e) { }
    }
}
/*****************选择基础信息******************/
function ChooseBaseData(sign, id) {
    obj = document.getElementById(id);
    objName = document.getElementById(id + "_Name");
    s_txtID = id;
    url = "/Common/BaseData/ChooseBaseData.aspx?sign=" + sign + "&selected=" + obj.value;
    qc.openWindow(url, 800, 600, true);
}

function set_BaseData(r) {
    obj = document.getElementById(s_txtID);
    objName = document.getElementById(s_txtID + "_Name");
    if (r != null && obj != null && objName != null) {
        obj.value = r[0];
        objName.value = r[1];
    }
}
/*****************选择竞争对手******************/
function ChooseCompetitor(txtID, txtName) {
    url = "/CRM/Sales/CompetitorList.aspx?mode=choosesingle&cid=" + txtID + "&cname=" + txtName;
    qc.openWindow(url, 800, 600, true);
}
/***************流程********************/
function ShowFlow(ddlistName, sModCode) {
    var obj = document.getElementById(ddlistName);
    if (obj.value == "-1") {
        alert('不使用流程');
    }
    else {
        FlowView(obj.value, sModCode);
    }
}
//流程查看
function FlowView(sFK_FlowID, sModCode) {
    if (sFK_FlowID != "" && sModCode != "") {
        //var win=OpenWindow('/OA/workflow/flowsetaddup.aspx?view=1&flowid='+sFK_FlowID+'&modcode='+sModCode,'流程信息',null,500,850);
        var win = qc.openWindow('/Common/wf/workflow/flowsetaddup.aspx?view=1&flowid=' + sFK_FlowID + '&modcode=' + sModCode, 850, 500, true);
    }
    else {
        alert('参数错误,无法查看流程信息！');
    }
}
function ShowStat(strTitle) {
    qc.openWindow('/Common/Component/StructAnalyseImage.aspx?sTitle=' + strTitle, 860, 560);
}
// 关闭当前窗口或返回上一窗口
function ReturnCloseWindow() {
    if (window.opener != null) {
        window.close();
    }
    else {
        if (document.getElementById("_ctl0_WorkForm_txtURL") == null) {
            history.back(-1);
        }
        else {
            window.location.href = document.getElementById("_ctl0_WorkForm_txtURL").value;
        }
    }
}
//-----------------------Begin机构、部门、人员Begin----------------------------//
// 修改：组织机构关联（显示部门）
function LoadDeptPersonnel(ddlParent, isPublic, ddlOrgId, ddlDeptId, ddlUserId) {
    var _ddlOrgId = ddlOrgId ? ddlOrgId : "_ctl0_WorkForm_ddlOrg";
    var _ddlDeptId = ddlDeptId ? ddlDeptId : "_ctl0_WorkForm_ddlDept";
    var _ddlUserId = ddlUserId ? ddlUserId : "_ctl0_WorkForm_ddlUser";

    var ddl = document.getElementById(_ddlDeptId);
    ddl.options.length = 0;
    var ddlSub = document.getElementById(_ddlUserId);
    if (isPublic != true) ddlSub.options.length = 0;
    dtb = null;
    str = ddlParent.value;
    if (str.length == 0) {
        qc.insertItemFirst(ddl, Language.SelfDefault, "");
        if (isPublic != true) qc.insertItemFirst(ddlSub, Language.SelfDefault, "");
    }
    else if (str == "-1") {
        qc.insertItemFirst(ddl, Language.All1, "-1");
        if (isPublic != true) qc.insertItemFirst(ddlSub, Language.All1, "-1");
    }
    else {
        if (str.indexOf("！") >= 0) {
            bindOrgDeptUser(_ddlDeptId, str, 1, function () {
                // 展开部门层次
                for (i = 0; i < ddl.options.length; i++) {
                    var deptId = ddl.options[i].value;
                    if (ddl.options[i].value.indexOf("!") < 0) {
                        qc.post('Common_Core.dll', 'Common_Core.MutiModel.CRM_BLL.UIHelper', { 'Code': 'DeptIDList_s', 'ParentCode': deptId }, function (data) {
                            if (data != null) {
                                if (data.length > 0) {
                                    bindDeptUserAddItems(_ddlDeptId, data);
                                }
                            }
                        });
                    }
                }
            });
            if (isPublic != true) {
                bindSelectData(_ddlUserId, '', true);
            }
        } else {
            var s_value = document.getElementById(_ddlOrgId).value;
            qc.post('Common_Core.dll', 'Common_Core.MutiModel.CRM_BLL.UIHelper', { "Code": "Dept_s", "ParentCode": "Organization", "parameterValue": s_value }, function (data) {
                if (data != null) {
                    bindSelectData(_ddlDeptId, data, true);
                    if (isPublic != true) {
                        bindSelectData(_ddlUserId, '', true);
                    }
                }
            });
        }
    }
    ddl.selectedIndex = -1;
    ddl.selectedIndex = 0;
    try {

        if (ddl.value == "") {
            LoadShareUser("");
        }
        else {
            var ddlShare = document.getElementById("_ctl0_WorkForm_ddlShare");
            ddlShare.options.length = 0;
            qc.insertItemFirst(ddlShare, Language.All1, "-1");
            ddlShare.selectedIndex = -1;
            ddlShare.selectedIndex = 0;
        }
    }
    catch (e) { }
}
// 组织机构关联（显示人员）
function LoadUserPersonnel(ddlParent, userID, callback, eleDeptID, eleUserID) {
    if (!eleDeptID) eleDeptID = "_ctl0_WorkForm_ddlDept";
    if (!eleUserID) eleUserID = "_ctl0_WorkForm_ddlUser";
    var ddl = document.getElementById(eleUserID);
    ddl.options.length = 0;
    var str = ddlParent.value;
    if (str.length == 0) {
        qc.insertItemFirst(ddl, Language.SelfDefault, "");
    }
    else if (str == "-1") {
        qc.insertItemFirst(ddl, Language.All1, "-1");
    }
    else {
        if (str.indexOf("!") >= 0) {
            bindOrgDeptUser(eleUserID, str, 2);
            if (userID) {
                var hidUserID = document.getElementById(userID);
                if (ddl && hidUserID) {
                    if (hidUserID.value != "") {
                        ddl.value = hidUserID.value;
                    }
                    if (ddl.value == "-1" || ddl.value == "") {
                        hidUserID.value = "-1";
                        ddl.value = "-1";
                    }
                    if (ddl.value == "" && ddlParent.value == "") {
                        hidUserID = "";
                        ddl.value = "";
                    }
                    if (typeof (callback) == "function") {
                        callback();
                    }
                }
            }
        }
        else {
            var s_value = document.getElementById(eleDeptID).value;
            qc.post('Common_Core.dll', 'Common_Core.MutiModel.CRM_BLL.UIHelper', { "Code": "Employee_s", "ParentCode": "Dept", "parameterValue": s_value }, function (data) {
                if (data != null) {
                    bindSelectData(eleUserID, data, true);
                    if (userID) {
                        var hidUserID = document.getElementById(userID);
                        if (ddl && hidUserID) {
                            if (hidUserID.value != "") {
                                ddl.value = hidUserID.value;
                            }
                            if (ddl.value == "-1" || ddl.value == "") {
                                hidUserID.value = "-1";
                                ddl.value = "-1";
                            }
                            if (ddl.value == "" && ddlParent.value == "") {
                                hidUserID = "";
                                ddl.value = "";
                            }
                            if (typeof (callback) == "function") {
                                callback();
                            }
                        }
                    }
                }
            });
        }
    }
    //以下为共享客户部分使用
    try {
        if (ddl.value == "") {
            // LoadShareUser("");
        }
        else {
            var ddlShare = document.getElementById("_ctl0_WorkForm_ddlShare");
            ddlShare.options.length = 0;
            qc.insertItemFirst(ddlShare, Language.All1, "-1");
            ddlShare.selectedIndex = -1;
            ddlShare.selectedIndex = 0;
        }
    }
    catch (e) {
    }
}
// 修改：组织机构关联（显示部门）
function LoadDeptPersonnelClient(ddlParent, isPublic, eleOrgID, eleDeptID, eleUserID, eleHidUserID, elePublicAppendID, elePublicOnelyID) {
    var share = document.getElementById("_ctl0_WorkForm_txtShareId");
    if (share) share.value = "";
    // 用户自定义控件方式
    if (!eleOrgID) eleOrgID = "_ctl0_WorkForm_ddlOrg";
    if (!eleDeptID) eleDeptID = "_ctl0_WorkForm_ddlDept";
    if (!eleUserID) eleUserID = "_ctl0_WorkForm_ddlUser";
    if (!eleHidUserID) eleHidUserID = "_ctl0_WorkForm_hidUserID";
    if (!elePublicAppendID) elePublicAppendID = "_ctl0_WorkForm_chkPublicAppend";
    if (!elePublicOnelyID) elePublicOnelyID = "_ctl0_WorkForm_chkPublicOnly";
    //
    document.getElementById(eleHidUserID).value = "";
    var ddl = document.getElementById(eleDeptID);
    ddl.options.length = 0;
    var ddlSub = document.getElementById(eleUserID);
    if (isPublic != true) ddlSub.options.length = 0;
    var dtb = null;
    var str = ddlParent.value;
    var pulicAppendObj = document.getElementById(elePublicAppendID);
    var publicOnlyObj = document.getElementById(elePublicOnelyID);
    if (str.length == 0) {
        qc.insertItemFirst(ddl, Language.SelfDefault, "");
        if (isPublic != true) qc.insertItemFirst(ddlSub, Language.SelfDefault, "");
        if (publicOnlyObj != null && pulicAppendObj != null) {
            pulicAppendObj.checked = false;
            publicOnlyObj.checked = false;
            pulicAppendObj.disabled = "disabled";
            publicOnlyObj.disabled = "disabled";
        }
    }
    else if (str == "-1") {
        qc.insertItemFirst(ddl, Language.All1, "-1");
        if (isPublic != true) qc.insertItemFirst(ddlSub, Language.All1, "-1");
        if (publicOnlyObj != null && pulicAppendObj != null) {
            pulicAppendObj.disabled = "";
            publicOnlyObj.disabled = "";
        }
    }
    else {
        if (publicOnlyObj != null && pulicAppendObj != null) {
            pulicAppendObj.disabled = "";
            publicOnlyObj.disabled = "";
        }
        if (str.indexOf("！") >= 0) {
            bindOrgDeptUser(eleDeptID, str, 1, function () {
                // 展开部门层次
                for (i = 0; i < ddl.options.length; i++) {
                    var deptId = ddl.options[i].value;
                    if (ddl.options[i].value.indexOf("!") < 0) {
                        qc.post('Common_Core.dll', 'Common_Core.MutiModel.CRM_BLL.UIHelper', { 'Code': 'DeptIDList_s', 'ParentCode': deptId }, function (data) {
                            if (data != null) {
                                if (data.length > 0) {
                                    bindDeptUserAddItems(eleDeptID, data);
                                }
                            }
                        });
                    }
                }
            });
            if (isPublic != true) bindSelectData(eleUserID, '', true);
        }
        else {
            var s_value = document.getElementById(eleOrgID).value;
            qc.post('Common_Core.dll', 'Common_Core.MutiModel.CRM_BLL.UIHelper', { 'Code': 'Dept_s', 'ParentCode': 'Organization', 'parameterValue': s_value }, function (data) {
                if (data != null) {
                    bindSelectData(eleDeptID, data, true);
                    if (isPublic != true) qc.insertItemFirst(ddlSub, Language.All1, "-1");
                }
            });
        }
    }
    ddl.selectedIndex = -1;
    ddl.selectedIndex = 0;
    try {
        if (ddl.value == "") {
            LoadShareUser("");
        }
        else {
            var ddlShare = document.getElementById("_ctl0_WorkForm_ddlShare");
            ddlShare.options.length = 0;
            qc.insertItemFirst(ddlShare, Language.All1, "-1");
            ddlShare.selectedIndex = -1;
            ddlShare.selectedIndex = 0;
        }
    }
    catch (e) { }
}
function LoadUserPersonnelClient(ddlParent, eleDeptID, eleUserID, eleHidUserID, elePublicAppendID, elePublicOnelyID) {
    var share = document.getElementById("_ctl0_WorkForm_txtShareId");
    if (share) share.value = "";
    // 控件方式
    if (!eleDeptID) eleDeptID = "_ctl0_WorkForm_ddlDept";
    if (!eleUserID) eleUserID = "_ctl0_WorkForm_ddlUser";
    if (!eleHidUserID) eleHidUserID = "_ctl0_WorkForm_hidUserID";
    if (!elePublicAppendID) elePublicAppendID = "_ctl0_WorkForm_chkPublicAppend";
    if (!elePublicOnelyID) elePublicOnelyID = "_ctl0_WorkForm_chkPublicOnly";

    document.getElementById(eleHidUserID).value = "";
    var ddl = document.getElementById(eleUserID);
    ddl.options.length = 0;
    var str = ddlParent.value;
    var pulicAppendObj = document.getElementById(elePublicAppendID); LoadShareUser
    var publicOnlyObj = document.getElementById(elePublicOnelyID);
    if (str.length == 0) {
        qc.insertItemFirst(ddl, Language.SelfDefault, "");
    }
    else if (str == "-1") {
        qc.insertItemFirst(ddl, Language.All1, "-1");
        if (publicOnlyObj != null && pulicAppendObj != null) {
            pulicAppendObj.disabled = "";
            publicOnlyObj.disabled = "";
        }
    }
    else {
        if (str.indexOf("!") >= 0) {
            bindOrgDeptUser(eleUserID, str, 2)
        }
        else {
            var s_value = document.getElementById(eleDeptID).value;
            qc.post('Common_Core.dll', 'Common_Core.MutiModel.CRM_BLL.UIHelper', { 'Code': 'Employee_s', 'ParentCode': 'Dept', 'parameterValue': s_value }, function (data) {
                if (data != null) {
                    bindSelectData(eleUserID, data, true);
                }
            });
        }
        if (publicOnlyObj != null && pulicAppendObj != null) {

            pulicAppendObj.disabled = "";
            publicOnlyObj.disabled = "";
        }
    }
    ddl.selectedIndex = -1;
    ddl.selectedIndex = 0;
    //以下为共享客户部分使用
    try {
        if (ddl.value == "") {
            LoadShareUser("");
        }
        else {
            var ddlShare = document.getElementById("_ctl0_WorkForm_ddlShare");
            ddlShare.options.length = 0;
            qc.insertItemFirst(ddlShare, Language.All1, "-1");
            ddlShare.selectedIndex = -1;
            ddlShare.selectedIndex = 0;
        }
    }
    catch (e) {
    }
}
//机构、部门、人员后有[仅公共客户/含公共客户]选项的调用此方法
function LoadUserPersonnel2() {
    var pulicAppendObj = document.getElementById("_ctl0_WorkForm_chkPublicAppend");
    var publicOnlyObj = document.getElementById("_ctl0_WorkForm_chkPublicOnly");
    var isPublic = document.getElementById("_ctl0_WorkForm_hidIsPublic");
    var ddlUser = document.getElementById("_ctl0_WorkForm_ddlUser");
    if (ddlUser != null) {
        if (ddlUser.value != "-1") {

            pulicAppendObj.checked = false;
            publicOnlyObj.checked = false;
            pulicAppendObj.disabled = "disabled";
            publicOnlyObj.disabled = "disabled";
        } else {
            pulicAppendObj.disabled = "";
            publicOnlyObj.disabled = "";
        }
    }
    if (isPublic != null && isPublic.value == "1") {
        var dept = document.getElementById("_ctl0_WorkForm_ddlDept");
        LoadUserPersonnel(dept, "_ctl0_WorkForm_hidUserID", function () {
            if (ddlUser.value != "-1") {
                pulicAppendObj.checked = false;
                publicOnlyObj.checked = false;
                pulicAppendObj.disabled = "disabled";
                publicOnlyObj.disabled = "disabled";
            } else {
                pulicAppendObj.disabled = "";
                publicOnlyObj.disabled = "";
            }
        });
    }
    if (isPublic != null)
        document.getElementById("_ctl0_WorkForm_hidIsPublic").value = "";
}
//机构、部门、人员后有[仅公共客户/含公共客户]选项的调用此方法
function LoadShareUser2(UserID) {
    document.getElementById("_ctl0_WorkForm_hidUserID").value = UserID;
    var pulicAppendObj = document.getElementById("_ctl0_WorkForm_chkPublicAppend");
    var publicOnlyObj = document.getElementById("_ctl0_WorkForm_chkPublicOnly");
    var orgObj = document.getElementById("_ctl0_WorkForm_ddlOrg");
    if (UserID != "") {
        if (UserID != "-1") {
            pulicAppendObj.checked = false;
            publicOnlyObj.checked = false;
            pulicAppendObj.disabled = "disabled";
            publicOnlyObj.disabled = "disabled";
        } else {
            pulicAppendObj.disabled = "";
            publicOnlyObj.disabled = "";
        }
    }
    if (orgObj != null) {
        if (orgObj.options.value == "") {
            pulicAppendObj.checked = false;
            publicOnlyObj.checked = false;
            pulicAppendObj.disabled = "disabled";
            publicOnlyObj.disabled = "disabled";
        }
    }
}
//-----------------------End机构、部门、人员End----------------------------//
//*******************************杂项******************************************//
//绑定Select[多用于查询][参数1：Select元素ID、参数2：绑定的数据(字符串格式)、参数3：是否查询全部、]
//ex：bindSelectData('_ctl0_WorkForm_ddlShare',dataStr,1,'1,2,5,7');
function bindSelectData(oSelect, dataStr, isSearch, fristValue) {
    if (document.getElementById(oSelect)) {
        var ddl = document.getElementById(oSelect);
        ddl.options.length = 0;
        if (isSearch) {
            if (fristValue) {
                switch (fristValue) {
                    case 1:
                        ddl.options.add(new Option(Language.All1, ''));
                        break;
                    default:
                        ddl.options.add(new Option(Language.All1, fristValue));
                        break;
                }
            } else {
                ddl.options.add(new Option(Language.All1, '-1'));
            }
        } else {
            if (fristValue) {
                switch (fristValue) {
                    case 1:
                        ddl.options.add(new Option(Language.PleaseSelect, ''));
                        break;
                    default:
                        ddl.options.add(new Option(Language.PleaseSelect, fristValue));
                        break;
                }
            } else {
                ddl.options.add(new Option(Language.PleaseSelect, '-1'));
            }
        }
        if (dataStr.length > 0) {
            var dataEval = eval(dataStr);

            for (var i = 0; i < dataEval.length; i++) {
                ddl.options.add(new Option(dataEval[i].sName, dataEval[i].id));
            }
        }
        ddl.selectIndex = 0;
    }
}
function bindOrgDeptUser(oSelect, dataStr, type, callback) {
    if (document.getElementById(oSelect)) {
        var ddl = document.getElementById(oSelect);
        ddl.options.length = 0;
        ddl.options.add(new Option(Language.All1, '-1'));
        var charS1 = '；';
        var charS2 = '！';
        switch (type) {
            case 1:
                charS1 = '；';
                charS2 = '！';
                break;
            case 2:
                charS1 = ';';
                charS2 = '!';
                break;
            default:
                charS1 = '|#|';
                charS2 = '|@|';
                break;
        }
        if (dataStr) {
            if (dataStr.length > 0) {
                var dataList = dataStr.split(charS1);
                for (var i = 0; i < dataList.length; i++) {
                    var dataEval = dataList[i].split(charS2);
                    ddl.options.add(new Option(dataEval[0], dataEval[1]));
                }
            }
        }
    }
    if (typeof (callback) == "function") {
        callback();
    }
}
function bindDeptUserAddItems(oSelect, dataStr) {
    if (document.getElementById(oSelect)) {
        var ddl = document.getElementById(oSelect);
        if (dataStr) {
            if (dataStr.length > 0) {
                var dataList = eval(dataStr);
                for (var i = 0; i < dataList.length; i++) {
                    ddl.options.add(new Option(dataList[i].sName, dataList[i].id));
                }
            }
        }
    }
}
function OpenCustCenter(custID) {
    qc.openCustCenter(custID);
}
function OpenCustCenterHms(custid) {
    url = "/Common/Client/ClientCenter.aspx?operation=edit&ID=" + custid + "&BT=Hms&mode=center";
    qc.openWindow(url, 1000, 650, true);
}

// 判断是否整形
function IsInt(value, test) {
    return IsNumber(value, "0123456789");
}

// 判断是否数值
function IsNumber(value, test, isMinus) {
    if (value != null && value.length > 0) {
        Num_Test = ".-0123456789";
        if (test != null && test.length > 0) Num_Test = test;

        // 检查是否负数
        if (isMinus == true && value.charAt(0) != "-") {
            return false;
        }

        Char_Index = -1;
        Char_Value = "";
        for (i = 0; i < value.length; i++) {
            Char_Value = value.charAt(i);
            Num_Index = Num_Test.indexOf(Char_Value);
            if (Num_Index == -1) {
                return false;
            }
            else if (Char_Value == "-" && Num_Index != 0)	// -号必须在首位
            {
                return false;
            }
        }
    }
    return true;
}
// 保存关闭并刷新父窗口
function SaveCloseWindow(prompt) {

    if (prompt != null && prompt == true) {
        alert(Language.OperateSucceed);
    }
    if (window.opener != null) {
        if (window.opener.__doPostBack != null && (document.URL.indexOf("operation=edit") >= 0 || (document.URL.indexOf("operation=add") >= 0 && document.URL.indexOf("popup=1") >= 0))) {
            if (opener.bindGrid)
                opener.bindGrid();
            else
                window.opener.__doPostBack("", "RefreshList");
        }
        else {
            window.opener.location.href = window.opener.location.href;
        }
        window.close();
    }
    else {
        if (document.getElementById("_ctl0_WorkForm_txtURL") == null) {
            history.back(-1);
        }
        else {
            window.parent.location.href = document.getElementById("_ctl0_WorkForm_txtURL").value;
        }
    }
}
function UpdateCacheTime() {
    var s_value = document.getElementById('_ctl0_WorkForm_txtCacheKey').value;
    qc.post("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.CacheManager", { "Key": "UpdateCacheTime_s", "parameterValue": s_value }, function (data) {

    });
}
/*****************************From ioa.js********************************/
function openSelectUserReceiver(lock, selectType, selectId, selectName, otherParam) {
    var url = '/Common/Mod/SelectReceiver.aspx?popup=1';
    if (lock) {
        if (lock.length > 0) url += '&lock=' + lock;
    }
    url += '&SelectedType=' + selectType;
    url += '&SelectedID=' + selectId;
    url += '&SelectedName=' + encodeURIComponent(selectName);
    if (otherParam) {
        if (otherParam.length > 0) url += otherParam;
    }
    return qc.openWindow(url, 640, 560, true);
}
function OpenWindow2(url, title, size, h, w) {
    if (h == null) h = 600;
    if (w == null) w = 900;
    if (size != null) {
        switch (size) {
            case 0: h = 300; w = 450; break;
            case 1: h = 450; w = 600; break;
            case 2: h = 550; w = 800; break;
            default: h = 600; w = 900; break
        }
    }
    window.open(url, title, "height=" + h + ",width=" + w + ",toolbar=no,status=yes,resizable=yes,scrollbars=yes");
}
function SetDataGridColumn(model) {
    url = "/Common/BaseData/DataGridColumnSetup.aspx?model=" + model;
    qc.openWindow(url, 620, 500, true);
    return false;
}
// 检查日期范围
function CheckDateRange(dateB, dateE) {
    if (dateB.length > 0 && dateE.length > 0) {
        var arrB = dateB.split("-");
        var arrE = dateE.split("-");
        var dtmB = new Date(arrB[0], arrB[1], arrB[2]);
        var dtmE = new Date(arrE[0], arrE[1], arrE[2]);

        if (dtmB > dtmE) {
            return false;
        }
    }
    return true;
}
// 检查录入
function CheckI(controlID, controlDesc, allowNull) { return Check(controlID, controlDesc, "I+", allowNull) }
function CheckN(controlID, controlDesc, allowNull) { return Check(controlID, controlDesc, "N+", allowNull) }
function Check(controlID, controlDesc, dataType, checkNull) {

    control = document.getElementById(controlID);
    if (control == null) return true;

    controlDesc = "[" + controlDesc + "]";
    controlValue = null;
    controlType = "";
    switch (controlType) {
        default: // "Text"
            controlValue = control.value;
            break;
    }

    controlValue = controlValue.replace(/\,/g, "");//去除金额中的,
    // 检查空值
    if (checkNull == true && (controlValue.length == 0 || controlValue == "0" || controlValue == "-1")) {
        alert(controlDesc + Language.NotEmpty + "。");
        qc.setFocus(control);
        return false;
    }

    // 检查数据类型
    switch (dataType) {
        case "I":
            if (!IsNumber(controlValue, "-0123456789")) {
                alert(controlDesc + "格式不正确，请录入整数。");
                qc.setFocus(control);
                return false;
            }
            break;
        case "I+":
            if (!IsNumber(controlValue, "0123456789")) {
                alert(controlDesc + "格式不正确，请录入正整数。");
                qc.setFocus(control);
                return false;
            }
            break;
        case "I-":
            if (!IsNumber(controlValue, "0123456789", false)) {
                alert(controlDesc + "格式不正确，请录入负整数。");
                qc.setFocus(control);
                return false;
            }
            break;
        case "N":
            if (!IsNumber(controlValue, ".-0123456789")) {
                alert(controlDesc + "格式不正确，请录入数值。");
                qc.setFocus(control);
                return false;
            }
            break;
        case "N+":
            if (!IsNumber(controlValue, ".0123456789")) {
                alert(controlDesc + "格式不正确，请录入正数。");
                qc.setFocus(control);
                return false;
            }
            break;
        case "N-":
            if (!IsNumber(controlValue, "-.0123456789", false)) {
                alert(controlDesc + "格式不正确，请录入负数。");
                qc.setFocus(control);
                return false;
            }
            break;
        default: // "C"
            if (controlValue.length == 0 || controlValue == "-1") {
                alert(controlDesc + Language.NotEmpty + "。");
                qc.setFocus(control);
                return false;
            }
            break;
    }
    return true;
}

// 打印
function Print() {
    document.getElementById("path").style.display = "none";
    document.getElementById("tdcontent").style.border = "none";
    // 隐藏按钮，隐藏下拉框
    var fonts = document.getElementsByTagName("span");
    for (i = 0; i < fonts.length; i++) {
        if (fonts[i].innerHTML.indexOf("*") >= 0) fonts[i].innerHTML = "";
    }
    var buttons = document.getElementsByTagName("input");
    for (i = 0; i < buttons.length; i++) {
        if ((buttons[i].type == "button" || buttons[i].type == "submit") && buttons[i].style.display == "") buttons[i].style.display = "none";
    }
    var selects = document.getElementsByTagName("select");
    for (i = 0; i < selects.length; i = 0) {
        if (selects[i].style.display == "") printSelectToText(selects[i]);
    }
    printSelectToText(document.getElementById("_ctl0_WorkForm_ddlBalanceModeID"));
    printSelectToText(document.getElementById("_ctl0_WorkForm_Account1_ddlAccountBank"));
    var accounBlock = document.getElementById("trAccountBank");
    if (accounBlock) accounBlock.style.display = "none";
    var btnblock = document.getElementById("btnblock");
    var imgLogo = document.getElementById("imgPrintLogo");
    if (imgLogo && imgLogo.title == "") {
        var lblWidth = 0
        var lblTmp = document.getElementById("_ctl0_WorkButton_lblAudited");
        if (lblTmp) lblWidth = lblTmp.offsetWidth;
        imgLogo.style.display = "";
        imgLogo.style.marginRight = ((btnblock.offsetLeft + btnblock.offsetWidth - 180 - lblWidth) / 2) - (lblWidth / 2) + "px";
    }
    // 网格
    var rows = document.getElementsByTagName("tr");
    for (i = 0; i < rows.length; i++) {
        if (rows[i].id.indexOf("_LastRow") >= 0) {
            rows[i].style.display = "none";
        }
    }
    // 流程
    if (document.getElementById("divFlow") != null) document.getElementById("divFlow").style.display = "none";
    // 添加附件
    if (document.getElementById("FileAttachModel_tdAtt") != null) document.getElementById("FileAttachModel_tdAtt").style.display = "none";
    // 页面自己的打印前处理
    if (window.PrePrint) window.PrePrint();
    // 如果有标题，隐藏IE的标题
    if (document.getElementById("lblTitle")) document.title = "";
    try {
        var frmlist = document.getElementsByTagName("iframe");
        for (var i = 0; i < frmlist.length; i++) {
            frmlist[i].style.display = "none";
            var div = document.createElement("div");
            div.innerHTML = frmlist[i].contentWindow.document.body.innerHTML;
            $(frmlist[i]).insertBefore(div)
        }
    } catch (e) { }
    window.print();
    document.getElementById("path").style.display = "";
    window.close();
}
function printSelectToText(e) {
    if (!e) return;
    var dataText = "";
    try { dataText = e.options[e.options.selectedIndex].text; }
    catch (ex) { }
    if (dataText.indexOf("请选择") != -1 || dataText.indexOf("Please") != -1) dataText = "";
    e.outerHTML = "<input readonly='readonly' class='Line' name='" + e.id + "' id='" + e.id + "' type='text' value='" + dataText + "' style='width:" + e.style.width + ";' />";
}

//UI

var bgC = new Array("#f4f4e0", "#fbfbf7", '#f0f0f0');
function bgMouseOver(obj) {
    if (obj.style.backgroundColor != bgC[0]) { obj.style.backgroundColor = bgC[2]; }
}
function bgMouseOut(obj) {
    if (obj.style.backgroundColor != bgC[0]) { obj.style.backgroundColor = bgC[1]; }
}
function bgMouseDown(obj) {
    if (obj.style.backgroundColor == bgC[0]) { obj.style.backgroundColor = bgC[1] } else { obj.style.backgroundColor = bgC[0]; }
}


/*------------------------------ Financial-------------------------------*/
// Installment
function OpenCustCenter_ByRecePayID(recepayid) {
    qc.openWindow("/Common/Client/ClientCenter.aspx?operation=edit&BT=Acc&mode=center&recepayid=" + recepayid, 1000, 600, true);
}
function ShowInstalment(id) {
    qc.openWindow("/CRM/RecePay/InstalmentEdit.aspx?operation=edit&id=" + id, 900, 600, true);
}
function OpenCustCenterAcc(custid) {
    qc.openWindow("/Common/Client/ClientCenter.aspx?operation=edit&ID=" + custid + "&BT=Acc&mode=center&popup=1", 1000, 600, true);
}
function InstalmentBalance(typeid, id) {
    url = GetRecePayBillUrl(typeid) + "&InstalmentID=" + id + "&settleopt=1";
    qc.openWindow(url, 900, 600, true);
}

// 根据账务类别返回单据url
function GetRecePayBillUrl(typeid) {
    switch (typeid) {
        case 1: return "/Common/Financial/ReceiptBillOpt.aspx?operation=add";
        case 2: return "/Common/Financial/PaymentBillOpt.aspx?operation=add";
    }
}
// 打开人员的考试明细
function viewExamDetail(id, type) {
    qc.openWindow("/Common/Exam/PersonalDetail.aspx?id=" + id + "&type=" + type);
}

//iframe自适应高度
function SetWinHeight(obj) {
    var win = obj;
    if (document.getElementById) {
        if (win && !window.opera) {
            if (win.contentDocument && win.contentDocument.body.offsetHeight)
                win.height = win.contentDocument.body.offsetHeight;
            else if (win.Document && win.Document.body.scrollHeight)
                win.height = win.Document.body.scrollHeight;
        }
    }
}
// 检测自定义字段必填
function chkCustomField(fields, id) {
    if (!fields) return true;
    var fieldArr = fields.split(',');
    for (var i in fieldArr) {
        var tmp = jQuery("#" + id + " [id*=" + fieldArr[i] + "]").filter('.Edit');
        switch (tmp.length) {
            case 0: continue;
            case 1:
                if (tmp.val() != "") continue;
                alert("[" + tmp.parent().prev().text().replace(/:|：/g, "") + "]" + Language.NotEmpty);
                tmp.focus();
                return false;
                break;
            default:
                var select = false;
                tmp.each(function () {
                    if (this.checked == false) return true;
                    select = true;
                    return false;
                });
                if (select == false) {
                    alert("[" + tmp.parent().prev().text().replace(/:|：/g, "") + "]" + Language.NotEmpty);
                    tmp.get(0).focus();
                    return false;
                }
                break;
        }
    }
    return true;
}
//zxg.2013-08-15.通过post请求webservice,参数url, method, json, callback,[async]
function RequestService(url, method, json, callback, async) {
    jQuery.ajax({
        type: "POST",
        contentType: "application/json",
        url: url + "/" + method + "?token=" + GetRequest("token") + "&r=" + Math.random(),
        data: json,
        dataType: "json",
        async: typeof (async) == 'undefined' ? true : async,
        cache: false,
        success: callback
    });
}
//朱现国.2014-05-16从请求地址中获取参数
function GetRequest(key) {
    var url = location.search;
    if (url.indexOf("?") != -1) {
        var array = url.substr(1).split("&");
        for (var index in array) {
            var item = array[index];
            var kv = item.split("=");
            if (kv.length == 2 && kv[0] == key) {
                return kv[1];
            }
        }
    }
    return "";
}
//zxg.2013-08-19.防止打开多个页面,依赖于eqccd.js
var handle;
function singleOpen(url, width, height) {
    if (handle && handle.open && !handle.closed) {
        handle.focus();
        return;
    }
    handle = qc.openWindow(url, width, height);
}
//zxg.2013-12-16.获取光标位置，兼容ie
function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range,
        textInputRange, len, endRange;

    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
        range = document.selection.createRange();

        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");

            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());

            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);

            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;

                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }

    return {
        start: start,
        end: end
    };
}

function addCookie(name, value, days, path) {   /**添加设置cookie**/
var name = escape(name);
    var value = escape(value);
    var expires = new Date();
    expires.setTime(expires.getTime() + days * 3600000 * 24);
    //path=/，表示cookie能在整个网站下使用，path=/temp，表示cookie只能在temp目录下使用
    path = path == "" ? "" : ";path=" + path;
    //GMT(Greenwich Mean Time)是格林尼治平时，现在的标准时间，协调世界时是UTC
    //参数days只能是数字型
    var _expires = (typeof days) == "string" ? "" : ";expires=" + expires.toUTCString();
    document.cookie = name + "=" + value + _expires + path;
}
function getCookieValue(name) {  /**获取cookie的值，根据cookie的键获取值**/
//用处理字符串的方式查找到key对应value
var name = escape(name);
    //读cookie属性，这将返回文档的所有cookie
    var allcookies = document.cookie;
    //查找名为name的cookie的开始位置
    name += "=";
    var pos = allcookies.indexOf(name);
    //如果找到了具有该名字的cookie，那么提取并使用它的值
    if (pos != -1) {                                             //如果pos值为-1则说明搜索"version="失败
        var start = pos + name.length;                  //cookie值开始的位置
        var end = allcookies.indexOf(";", start);        //从cookie值开始的位置起搜索第一个";"的位置,即cookie值结尾的位置
        if (end == -1) end = allcookies.length;        //如果end值为-1说明cookie列表里只有一个cookie
        var value = allcookies.substring(start, end); //提取cookie的值
        return (value);                           //对它解码
    } else {  //搜索失败，返回空字符串
        return "";
    }
}
function deleteCookie(name, path) {   /**根据cookie的键，删除cookie，其实就是设置其失效**/
var name = escape(name);
    var expires = new Date(0);
    path = path == "" ? "" : ";path=" + path;
    document.cookie = name + "=" + ";expires=" + expires.toUTCString() + path;
}

function IsExist(colName) {
    var obj = jQuery("#_ctl0_WorkForm_CustomerContact_" + colName);
    var objSpan = obj.parent().find("#CustomerContact_" + colName);
    if (obj.val() == "") {
        objSpan.text("");
    } else {
        qc.post("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.CustomerMgr", { method: "IsExistAjax", col: colName, val: obj.val() }, function (data) {
            objSpan.text(data);
        });
    }
}

//模糊查询
function chkRepeat(formName, fieldName) {
    var ele = jQuery("#_ctl0_WorkForm_" + formName + "_" + fieldName);
    if (ele.val().length == 0) return;
    ele.val(ele.val().replace(/(^\s*)|(\s*$)/g, ""));
    qc.asyncPost("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.Client", { Key: "RepeatClient", form: formName, field: fieldName, parameterValue: ele.val() },
        function (resVal) {
            if (resVal == "")
                qc.openWindow('/Common/Client/ClientDetectList.aspx?fromName=' + formName + "&fieldName=" + fieldName + "&" + fieldName + '=' + encodeURIComponent(ele.val()), 760, 540);
            else
                alert(resVal);
        });
}
//同名查询
function chkSame(formName, fieldName) {
    var ele = jQuery("#_ctl0_WorkForm_" + formName + "_" + fieldName);
    var objSpan = ele.parent().find("span:first");
    if (ele.val().length == 0) {
        objSpan.text("");
    }
    else {
        ele.val(ele.val().replace(/(^\s*)|(\s*$)/g, ""));
        qc.post("Common_Core.dll", "Common_Core.MutiModel.CRM_BLL.CustomerMgr", { method: "SameClient", form: formName, field: fieldName, parameterValue: ele.val() }, function (data) {
            objSpan.text(data);
        });
    }
}

function jsonDateFormat(jsonDate) {//json日期格式转换为正常格式 'yyy-MM-dd'
    try {
        var date = new Date(parseInt(jsonDate.replace("/Date(", "").replace(")/", ""), 10));
        var month = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
        var day = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
        return date.getFullYear() + "-" + month + "-" + day ;
    } catch (ex) {
        return "";
    }
}

//显示核销单
function ShowVerifyBill(id, para) {
    url = "/Common/Financial/VerificationBillOpt.aspx?operation=edit&id=" + id;
    if (para != null) {
        if (para.substring(0, 1) != "&") url = url + "&";
        url = url + para;
    }
    qc.openWindow(url, 900, 600, false, false);
}