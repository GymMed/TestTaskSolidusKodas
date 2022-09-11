<template>
    <PageComponent title="Dashboard">
        <form class="mt-8 space-y-6" @submit="editor_to_pdf">
            <editor id="tinyMCE1" :init="{plugins: 'export lists link image table code help wordcount'}"/>
            <button @click="generate_htmlToPdf_library()" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Get PDF(Frontend htmltopdf)</button>
            <button @click="generate_jsfPDF_library()" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Get PDF(Frontend jsfpdf)</button>
            <button @click="backend_generated_pdf()" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Get PDF(Backend to Frontend)</button>
        </form>
    </PageComponent>
</template>

<script setup>
import PageComponent from "../components/PageComponent.vue";
import Editor from "@tinymce/tinymce-vue";
import pdfMake from 'pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import htmlToPdfmake from 'html-to-pdfmake';
import jsPDF from "jspdf";
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const router = useRouter();
const store = useStore();

function editor_to_pdf(ev)
{
    ev.preventDefault();
}
function generate_htmlToPdf_library()
{
    var tinyMCEcontent = tinyMCE.get('tinyMCE1').getContent();
    var html = htmlToPdfmake(tinyMCEcontent);
    const documentDefinition = { content: html };
    pdfMake.vfs = pdfFonts.pdfMake.vfs;
    pdfMake.createPdf(documentDefinition).download("testTaskPDF.pdf");
}

function generate_jsfPDF_library()
{
    var tinyMCEcontent = tinyMCE.get('tinyMCE1').getContent();
    var doc = new jsPDF('p', 'pt', 'a4');

    doc.html(tinyMCEcontent, {
                callback: function (pdf) {
                    pdf.save('testTaskjsfPDF.pdf');
                }
            });
}

function backend_generated_pdf()
{
    var tinyMCEcontent = tinyMCE.get('tinyMCE1').getContent();
    store.dispatch("get_pdf", tinyMCEcontent)
    .then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'TestTaskBackend.pdf');
        document.body.appendChild(link);
        link.click();
    }).catch(err => {
        if(err.response.status == 401)
        {
            store.commit("logout");
            router.push({
                name: 'Login'
            })
        }
    })
}
</script>

<style scoped>
    
</style>