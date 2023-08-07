<?php

namespace App\Traits;

trait CommonTrait
{

	public function successResponse($message = null, $data = [], $code = 200)
	{
		return response()->json([
			'status' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}

	public function errorResponse($message = null, $code = 401)
	{
		return response()->json([
			'status' => false,
			'message' => $message,
			'data' => []
		], $code);
	}

	public function successResponseArr($message = 'success', $data = [])
	{
		return [
			'status' => true,
			'message' => $message,
			'data' => $data,
		];
	}

	public function errorResponseArr($message = 'Something went wrong!!', $errors = [])
	{
		return [
			'status' => false,
			'message' => $message,
			'data' => $errors
		];
	}


	public function timestampColumns($table)
	{
		$table->integer('created_by')->nullable();
		$table->timestamp('created_at')->nullable();
		$table->string('created_ip')->nullable();
		$table->integer('updated_by')->nullable();
		$table->timestamp('updated_at')->nullable();
		$table->string('updated_ip')->nullable();
		$table->integer('deleted_by')->nullable();
		$table->softDeletes();
		$table->string('deleted_ip')->nullable();
		return $table;
	}

	public function actionHtml($baseurl, $id, $actionDelete = true)
	{
		if ($actionDelete) {
			$html = '<div class="actions-a" data-id="' . $id . '" data-url="' . $baseurl . '">
			<a class="module_delete_record"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect width="32" height="32" rx="16" fill="#E96060"/>
			<path d="M20 12L12 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M12 12L20 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg></a></div>
			';
		} else {
			$html = '<div class="actions-a" data-id="' . $id . '" data-url="' . $baseurl . '">
			<a class="module_edit_record"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect width="32" height="32" rx="16" fill="#32297C"/>
			<g clip-path="url(#clip0_1835_2480)">
			<path d="M19.3335 9.99999C19.5086 9.8249 19.7165 9.686 19.9452 9.59124C20.174 9.49648 20.4192 9.44771 20.6668 9.44771C20.9145 9.44771 21.1596 9.49648 21.3884 9.59124C21.6172 9.686 21.8251 9.8249 22.0002 9.99999C22.1753 10.1751 22.3142 10.383 22.4089 10.6117C22.5037 10.8405 22.5524 11.0857 22.5524 11.3333C22.5524 11.5809 22.5037 11.8261 22.4089 12.0549C22.3142 12.2837 22.1753 12.4916 22.0002 12.6667L13.0002 21.6667L9.3335 22.6667L10.3335 19L19.3335 9.99999Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</g>
			<defs>
			<clipPath id="clip0_1835_2480">
			<rect width="16" height="16" fill="white" transform="translate(8 8)"/>
			</clipPath>
			</defs>
			</svg></a>

			<a class="module_view_record"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect width="32" height="32" rx="16" fill="#9B8FFE"/>
			<g clip-path="url(#clip0_1835_2483)">
			<path d="M8.6665 16C8.6665 16 11.3332 10.6667 15.9998 10.6667C20.6665 10.6667 23.3332 16 23.3332 16C23.3332 16 20.6665 21.3333 15.9998 21.3333C11.3332 21.3333 8.6665 16 8.6665 16Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M16 18C17.1046 18 18 17.1046 18 16C18 14.8954 17.1046 14 16 14C14.8954 14 14 14.8954 14 16C14 17.1046 14.8954 18 16 18Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</g>
			<defs>
			<clipPath id="clip0_1835_2483">
			<rect width="16" height="16" fill="white" transform="translate(8 8)"/>
			</clipPath>
			</defs>
			</svg></a>
			</div>
			';
		}
		return $html;
	}

	public function statusHtml($row)
	{
		$statusText = $row->status == 1 ? "Active" : "Inactive";
		$statusClass = $row->status == 1 ? "badge-primary" : " badge-danger";
		$status = "<span class='text-md badge badge-pill $statusClass'>$statusText</span>";
		return $status;
	}

	public function jsonResponse($message = null, $code = 401)
	{
		return response()->json($message, $code);
	}
}
