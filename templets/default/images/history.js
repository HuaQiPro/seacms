/*!
 * Copyright 2016-2018 http://v.shoutu.cn
 * Email 726662013@qq.com,admin@shoutu.cn
 */
$(document).ready(function($){
    var recente=$.cookie("recente");
    var len=0;
    var canadd=true;
    // $.cookie("recente",null,{expires:-1,path: '/'});
    // alert(recente);
    if(recente){
        recente = eval("("+recente+")"); 
        len=recente.length;
        $(recente).each(function(){
            if(vod_name==this.vod_name){   //已记录则修改
                canadd=false;
                var json="[";
                $(recente).each(function(i){
                    var temp_name,temp_url,temp_part;
                    if(this.vod_name==vod_name){
                        temp_name=vod_name;
                        temp_url=vod_url;
                        temp_part=vod_part;
                    }else{
                        temp_name=this.vod_name;
                        temp_url=this.vod_url; 
                        temp_part=this.vod_part;
                    }
                    json+="{\"vod_name\":\""+temp_name+"\",\"vod_url\":\""+temp_url+"\",\"vod_part\":\""+temp_part+"\"}";
                    if(i!=len-1)
                        json+=",";
                })
                json+="]";
                $.cookie("recente",json,{path:"/",expires:(2)});
                return false;
            }
        });
    }
    if(canadd){   //无记录则添加
        var json="[";
        var start=0;
        var isfirst="]";
        isfirst=!len?"]":",";
        json+="{\"vod_name\":\""+vod_name+"\",\"vod_url\":\""+vod_url+"\",\"vod_part\":\""+vod_part+"\"}"+isfirst;
        if(len>9)
            len-=1;
        for(i=0;i<len-1;i++){
            json+="{\"vod_name\":\""+recente[i].vod_name+"\",\"vod_url\":\""+recente[i].vod_url+"\",\"vod_part\":\""+recente[i].vod_part+"\"},";
        }
        if(len>0){
            json+="{\"vod_name\":\""+recente[len-1].vod_name+"\",\"vod_url\":\""+recente[len-1].vod_url+"\",\"vod_part\":\""+recente[len-1].vod_part+"\"}]";
        }
        $.cookie("recente",json,{path:"/",expires:(2)});
    }
})