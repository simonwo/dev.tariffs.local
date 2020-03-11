/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


////////////////////////////////////////////////////////////////////////////////
//////////////////////////SPLIT LABEL CHART_2///////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
var insertLinebreaks = function (d) {
    var el = d3.select(this);
//    var words = d.split(' ');      
    el.text('');

    for (var i = 0; i < d.length; i++) {
        var tspan = el.append('tspan').text(
                function (){                           
//return  d.split('-')[i];                    
//if (d === 'No employees (registered for VAT or PAYE)'){
//    return d.replace('No employees (registered for VAT or PAYE)','No employees~(registered for VAT or PAYE)').split('~')[i];}
//else {return d.split(' ')[i];}

  switch (d)
{
       
        case "prefer not to say": 
        return   d.replace('prefer not to say','prefer not~to say').split('~')[i];
        break;
   
        case "East of England": 
        return   d.replace('East of England','East of~England').split('~')[i];
        break;
   
        case "yorkshire and the humber": 
        return   d.replace('yorkshire and the humber','yorkshire~and the~humber').split('~')[i];
        break;
   
        case "not a member or supporter": 
        return   d.replace('not a member or supporter','not a member~or supporter').split('~')[i];
        break;
            
       
 default: 
     return d.split(' ')[i];
//     return d;
     ;
  
}


                     
                    
    });
    
   
        if (i > 0)
            tspan.attr('x', 0).attr('dy', '15');
    }
};