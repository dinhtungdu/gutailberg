document.addEventListener("DOMContentLoaded",(function(){const e=document.getElementById("fetch-templates"),t=document.getElementById("generate-css"),n=document.getElementById("clear-css");e.addEventListener("click",(function n(l){l.preventDefault(),e.textContent="Fetching..",fetch(`${ajaxurl}?action=gutailberg_get_fse_templates`).then((e=>e.json())).then((({data:l})=>{console.log("Fetched templates:",l.templates),document.getElementById("fse-templates").innerHTML=l.contents.replaceAll(/"className":"(.+)"/gm,' /--\x3e<div class="$1"></div>\x3c!-- '),e.textContent="Fetched!",e.classList.remove("button-secondary"),e.classList.add("button-disabled"),t.classList.remove("hidden"),e.removeEventListener("click",n)})).catch((e=>console.log(e)))})),t.addEventListener("click",(function(e){e.preventDefault();const n=document.evaluate("//style[contains(text(),'.\\!hidden')]",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;n||console.log("There is something wrong extracting Tailwind CSS from the current page."),document.getElementById("gutailberg_field_tailwind_output").value=n.textContent,t.textContent="Done! Don't forget to save the settings below ^^."})),n.addEventListener("click",(function(e){e.preventDefault(),document.getElementById("gutailberg_field_tailwind_output").value=""}))}));