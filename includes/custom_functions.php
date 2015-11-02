function required ($name = NULL, $message = NULL) {
	if ($name != NULL && $message != NULL) {
		if (empty($pData_['$name'])) {
			$isValid = false;
			$_SESSION['eMsg'][] = $message;
			return $isValid;
		}
	}
}