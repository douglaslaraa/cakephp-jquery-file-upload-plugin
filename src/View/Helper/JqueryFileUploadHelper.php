<?php
namespace JqueryFileUpload\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Filesystem\Folder;

/**
 * JqueryFileUpload helper
 */
class JqueryFileUploadHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function images($dir = 'files', $return = false)
    {
        if (is_dir(WWW_ROOT . $dir)) {
            $folder = new Folder(WWW_ROOT . $dir);
            $files = $folder->find('.*', true);

            if ($return)
                return $files;

            foreach ($files as $file) {
                echo $this->Html->image('/'. $dir . $file);
            }
        }
    }

    public function form()
    {
        echo $this->getForm();
        echo $this->getGallery();
        echo $this->getTemplateUpload();
        echo $this->getTemplateDownload();

        // $this->loadStyles();
        // $this->loadScripts();
    }

    public function getForm()
    {
        return '<form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-lg-7">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Add files...</span>
                        <input type="file" name="files[]" multiple>
                    </span>
                    <button type="submit" class="btn btn-primary start">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start upload</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel upload</span>
                    </button>
                    <button type="button" class="btn btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>
                    <input type="checkbox" class="toggle">
                    <!-- The global file processing state -->
                    <span class="fileupload-process"></span>
                </div>
                <!-- The global progress state -->
                <div class="col-lg-5 fileupload-progress fade">
                    <!-- The global progress bar -->
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <!-- The extended global progress state -->
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
        </form>';
    }

    public function getGallery()
    {
        return '<!-- The blueimp Gallery widget -->
            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">‹</a>
                <a class="next">›</a>
                <a class="close">×</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
            </div>';
    }

    public function getTemplateUpload()
    {
        return '<!-- The template to display files available for upload -->
            <script id="template-upload" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-upload fade">
                    <td>
                        <span class="preview"></span>
                    </td>
                    <td>
                        <p class="name">{%=file.name%}</p>
                        <strong class="error text-danger"></strong>
                    </td>
                    <td>
                        <p class="size">Processing...</p>
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                    </td>
                    <td>
                        {% if (!i && !o.options.autoUpload) { %}
                            <button class="btn btn-primary start" disabled>
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start</span>
                            </button>
                        {% } %}
                        {% if (!i) { %}
                            <button class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                            </button>
                        {% } %}
                    </td>
                </tr>
            {% } %}
            </script>';
    }

    public function getTemplateDownload()
    {
        return '<!-- The template to display files available for download -->
            <script id="template-download" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-download fade">
                    <td>
                        <span class="preview">
                            {% if (file.thumbnailUrl) { %}
                                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                            {% } %}
                        </span>
                    </td>
                    <td>
                        <p class="name">
                            {% if (file.url) { %}
                                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a>
                            {% } else { %}
                                <span>{%=file.name%}</span>
                            {% } %}
                        </p>
                        {% if (file.error) { %}
                            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                        {% } %}
                    </td>
                    <td>
                        <span class="size">{%=o.formatFileSize(file.size)%}</span>
                    </td>
                    <td>
                        {% if (file.deleteUrl) { %}
                            <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}>
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete</span>
                            </button>
                            <input type="checkbox" name="delete" value="1" class="toggle">
                        {% } else { %}
                            <button class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                            </button>
                        {% } %}
                    </td>
                </tr>
            {% } %}
            </script>';
    }

    public function loadStyles()
    {
        $styles = $this->getStyles();

        echo $this->Html->css($styles);

    }

    public function getStyles()
    {
        return [
            'JqueryFileUpload.bootstrap.min',
            'JqueryFileUpload.style',
            'JqueryFileUpload.blueimp-gallery.min',
            'JqueryFileUpload.jquery.fileupload',
            'JqueryFileUpload.jquery.fileupload-ui'
        ];
    }

    public function loadScripts()
    {
        $scripts = $this->getScripts();

        echo $this->Html->script($scripts);
    }

    public function getScripts()
    {
        return [
            'JqueryFileUpload.jquery.min',
            'JqueryFileUpload.jquery.ui.widget',
            'JqueryFileUpload.tmpl.min',
            'JqueryFileUpload.load-image.all.min',
            'JqueryFileUpload.canvas-to-blob.min',
            'JqueryFileUpload.bootstrap.min',
            'JqueryFileUpload.jquery.blueimp-gallery.min',
            'JqueryFileUpload.jquery.iframe-transport',
            'JqueryFileUpload.jquery.fileupload',
            'JqueryFileUpload.jquery.fileupload-process',
            'JqueryFileUpload.jquery.fileupload-image',
            'JqueryFileUpload.jquery.fileupload-audio',
            'JqueryFileUpload.jquery.fileupload-video',
            'JqueryFileUpload.jquery.fileupload-validate',
            'JqueryFileUpload.jquery.fileupload-ui',
            'JqueryFileUpload.main',
        ];
    }

}
